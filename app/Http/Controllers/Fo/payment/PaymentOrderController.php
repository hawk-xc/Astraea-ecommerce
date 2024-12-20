<?php

namespace App\Http\Controllers\Fo\payment;

use App\Models\ProductColor;

use App\Http\Controllers\Controller;
use App\Repositories\AboutUsRepository;
use App\Repositories\AppFeeRepository;

use App\Repositories\ContactUsRepository;
use App\Repositories\DiscountCostumerRepository;
use App\Repositories\OrderDetailRepository;
use App\Repositories\OrderHampersRepository;

use App\Repositories\OrderRepository;

use App\Repositories\PaymentDetailRepository;

use App\Repositories\ProductRepository;

use App\Repositories\ShippingRepository;
use Carbon\Carbon;
use Helper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

use \App\Models\BannerView as BannerModel;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;


class PaymentOrderController extends Controller
{
    private ContactUsRepository $contactUsRepository;
    private AboutUsRepository $aboutUsRepository;
    private PaymentDetailRepository $paymentDetailRepository;
    private OrderRepository $orderRepository;
    private OrderHampersRepository $orderHRepository;
    private OrderDetailRepository $orderDetailRepository;
    private ShippingRepository $shippingRepository;
    private DiscountCostumerRepository $d_customer_repository;
    private ProductRepository $productRepository;
    private AppFeeRepository $appFeeRepository;

    protected $data = array();
    public function __construct(ContactUsRepository $contactUsRepository, AboutUsRepository $aboutUsRepository, PaymentDetailRepository $paymentDetailRepository, OrderRepository $orderRepository, OrderHampersRepository $orderHRepository, OrderDetailRepository $orderDetailRepository, ShippingRepository $shippingRepository, DiscountCostumerRepository $d_customer_repository, ProductRepository $productRepository, AppFeeRepository $appFeeRepository)
    {
        $this->contactUsRepository = $contactUsRepository;
        $this->aboutUsRepository = $aboutUsRepository;
        $this->paymentDetailRepository = $paymentDetailRepository;
        $this->orderRepository = $orderRepository;
        $this->orderHRepository = $orderHRepository;
        $this->orderDetailRepository = $orderDetailRepository;
        $this->shippingRepository = $shippingRepository;
        $this->d_customer_repository = $d_customer_repository;
        $this->productRepository = $productRepository;
        $this->appFeeRepository = $appFeeRepository;
        $this->data['title'] = 'Payment';
        $this->data['view_directory'] = "guest.feature.payment.product";
    }

    public function index(String $order)
    {
        try {
            $no_nota = Crypt::decryptString($order);
        } catch (Illuminate\Contracts\Encryption\DecryptException $e) {
            $no_nota = null;
        }

        //data untuk ditampilkan
        $ref = $this->data;
        $data['contact'] = $this->contactUsRepository->getById('1');
        $data['about'] = $this->aboutUsRepository->getById('1');
        $data['banner'] = BannerModel::first()->pluck('images');

        //order
        $data['order'] = $this->orderRepository->getByNota($no_nota);

        //jika sudah ada link pembayaran maka akan diarahkan
        if (isset($data['order']['payment_link'])) {
            return redirect()->route('shop-product.index')->with('toast_success', 'Selamat berbelanja kembali');
        }

        //shiping
        $data['shipping'] = $this->shippingRepository->getById($data['order']['id']);
        $data['id_destination'] = auth()->guard('customer')->user()['district_id'];
        $data['addres'] = Auth()->guard('customer')->user()->address;

        //app fee
        $data['app_fee'] = $this->appFeeRepository->getById(1)['fee_amount'];

        //total price
        $data['total_price'] = ($data['order']['shipping']  + $data['app_fee']) + ($data['order']['sub_total_price'] * (1 - $data['order']['discount_amount'] / 100));

        $data['total_price'] = Helper::to_rupiah($data['total_price']);
        $data['app_fee'] = Helper::to_rupiah($data['app_fee']);

        //variable yang digunakan selector
        $data['id_nota'] = $order;
        $data['id_order'] = Crypt::encryptString($data['order']['id']);

        return view($this->data['view_directory'] . '.index', compact('ref', 'data'));
    }
    public function create(Request $request, String $order)
    {
        try {
            $no_nota = Crypt::decryptString($order);
        } catch (Illuminate\Contracts\Encryption\DecryptException $e) {
            $no_nota = null;
        }
        $data['banner'] = BannerModel::first()->pluck('images');

        //data
        $data['order'] = $this->orderRepository->getByNota($no_nota);

        if (isset($data['order']['payment_link'])) {
            return redirect($data['order']['payment_link']);
        }

        $data['shipping'] = $this->shippingRepository->getById($data['order']['id']);
        $data['discount'] = 0;
        $data['app_fee'] = $this->appFeeRepository->getById(1)['fee_amount'];

        //shipping checking
        if (!isset($data['shipping']['price'])) {
            return redirect()->back()->with('toast_warning', 'Cek ongkir terlebih dahulu');
        }
        //checking data discount
        $reccord['id_cutomer'] = Auth()->guard('customer')->user()->id;

        $coupon = $this->d_customer_repository->getByCodePromo($reccord['id_cutomer'], $data['order']['code_discount']);

        //menggabungkan date dan time
        $startDateTime = isset($coupon['discountData']['start_date']) ? Carbon::createFromFormat('Y-m-d H:i:s', $coupon['discountData']['start_date'] . ' ' . $coupon['discountData']['start_time']) : '';
        $endDateTime = isset($coupon['discountData']['end_date']) ?  Carbon::createFromFormat('Y-m-d H:i:s', $coupon['discountData']['end_date'] . ' ' . $coupon['discountData']['end_time']) : '';
        $currentDateTime = Carbon::now();

        //checking berlaku discount
        if (isset($coupon['discountData']['is_active'])) {
            if (($startDateTime >= $currentDateTime) || ($endDateTime <= $currentDateTime)  || ($coupon['discountData']['is_active'] != '1')) {
                if ($coupon['discountData']['id'] != 'DIS-20240000000000000001') {
                    return redirect()->back()->with('toast_warning', 'Coupon discount tidak tersedia');
                }
            }

            $data['discount'] = $coupon['discountData']['discount_amount'];
        }


        //checking data product
        $data['detail_order'] = $this->orderDetailRepository->orderList($data['order']['id']);

        // Assuming $data['detail_order'] can either be an array with multiple items or a single item

        // dd(count($data['detail_order']));
        if (count($data['detail_order']) > 1) {
            // Case when there are multiple products in the order (using foreach)
            foreach ($data['detail_order'] as $order_product) {
                // Ensure 'color' and 'id' are set before using them
                if (isset($order_product['color']['id'])) {
                    $color_id = str_replace("COL-", "", $order_product['color']['id']);

                    // Check for product color stock
                    $productColor = ProductColor::select('count')
                        ->where('product_id', $order_product['product_data']['id'])
                        ->where('color_id', $color_id)
                        ->first();

                    if (!$productColor) {
                        return redirect()->back()->with('toast_warning', 'Product warna tidak ditemukan');
                    }

                    $productColorCount = $productColor->count;

                    if ($productColorCount < $order_product['quantity']) {
                        return redirect()->back()->with('toast_warning', 'Product stok tidak tersedia');
                    }
                } else {
                    return redirect()->back()->with('toast_warning', 'Warna produk tidak valid');
                }
            }
        } else {
            // Case when there is only one product in the order (no need for foreach)
            $order_product = $data['detail_order'][0]; // Since it's a single product, we don't need to loop

            if (isset($order_product['color']['id'])) {
                $color_id = str_replace("COL-", "", $order_product['color']['id']);

                // Check product color stock
                $productColor = ProductColor::select('count')
                    ->where('product_id', $order_product['product_data']['id'])
                    ->where('color_id', $color_id)
                    ->first();

                if (!$productColor) {
                    return redirect()->back()->with('toast_warning', 'Product warna tidak ditemukan');
                }

                $productColorCount = $productColor->count;

                if ($productColorCount < $order_product['quantity']) {
                    return redirect()->back()->with('toast_warning', 'Product stok tidak tersedia');
                }
            } else {
                return redirect()->back()->with('toast_warning', 'Warna produk tidak valid');
            }
        }

        //validasi description
        $reccord = $request->validate([
            "description" => ['nullable', 'string', 'max:255'],
        ], [], [
            "description" => "Description",
        ]);

        $reccord['description'] = '" ' . $reccord['description'] . ' "';

        //menghitung total
        $total_price = ($data['shipping']['price'] + $data['app_fee']) + ($data['order']['sub_total_price'] * (1 - $data['discount'] / 100));

        try {
            //membuat link
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ' . base64_encode(env('XENDIT_SECRET_KEY') . ':'),
            ])->post('https://api.xendit.co/v2/invoices', [
                'external_id' => $no_nota,
                'amount' => $total_price,
                'payer_email' => auth()->guard('customer')->user()->email,
                'description' => $reccord['description'],
                'success_redirect_url' => route('fo.home'),
            ]);

            if ($response->failed()) {
                // Log seluruh respons dari API untuk melihat error detail
                \Log::error('Xendit API error response:', $response->json());

                // Opsional: Menampilkan pesan error yang lebih spesifik
                $errorMessage = $response->json()['message'] ?? 'Failed to create invoice, please check your production setup.';
                return redirect()->back()->with('toast_warning', $errorMessage);
            }

            if (!isset($response->json()['invoice_url'])) {
                \Log::error('Missing invoice_url in response:', $response->json());
                return redirect()->back()->with('toast_warning', 'Invoice creation failed, please try again.');
            }

            //apply discount
            if (isset($coupon['discountData']['is_active'])) {
                $this->d_customer_repository->edit($coupon['id'], [
                    'is_used' => '1'
                ]);
            }

            //update cart
            $waktuSekarang = Carbon::now()->toDateTimeString();
            $this->orderRepository->edit($data['order']['id'], [
                'discount_amount' => $data['discount'],
                'app_admin' => $data['app_fee'],
                'order_date'  => $waktuSekarang,
                'total_price' => $total_price,
                'status' => 'UNPAID',
                'payment_link' => $response->json()['invoice_url'],
            ]);

            //update stok barang
            foreach ($data['detail_order'] as $order_product) {
                try {
                    // Hilangkan "COL-" dari color_id jika ada
                    $color_id = str_replace("COL-", "", $order_product['color']['id']);

                    // Pastikan color_id tidak null atau kosong
                    if ($color_id) {
                        $productColor = ProductColor::where('product_id', $order_product['product_data']['id'])
                            ->where('color_id', $color_id)
                            ->first();

                        if ($productColor) {
                            // Kurangi count dengan jumlah yang dipesan
                            $newCount = $productColor->count - $order_product['quantity'];

                            // Update count di ProductColor jika hasil pengurangan valid
                            if ($newCount >= 0) {
                                $productColor->update(['count' => $newCount]);
                            }
                        }
                    }
                } catch (\Exception $e) {
                    // Abaikan kesalahan dan lanjutkan iterasi berikutnya
                    // Anda bisa menambahkan log error jika ingin memonitor kesalahan
                    Log::warning('Terjadi error saat memproses detail order', [
                        'order_product' => $order_product,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            // Mengambil respons dari API Xendit
            return redirect($response->json()['invoice_url']);
        } catch (Exception $e) {
            return redirect()->back()->with('toast_warning', 'Terjadi Kesahalahan' . $e);
        }
    }

    public function handleCallback(Request $request)
    {
        $getToken = $request->headers->get('x-callback-token');
        $callbackToken = env('XENDIT_CALLBACK_TOKEN');

        // try {
        //     return response()->json([
        //         'status' => 'success',
        //         'message' => 'Callback success',
        //         'token' => $getToken,
        //     ], Response::HTTP_OK);
        // } catch (\Throwable $e) {
        // }
        $data = $request->all();

        //create detail payment
        $record = [
            'amount' => $data['amount'],
            'status' => $data['status'],
            'paid_at' => $data['paid_at'],
            'description' => $data['description'],
            'external_id' => $data['external_id'],
            'payer_email' => $data['payer_email'],
            'payment_method' => $data['payment_method'],
            'payment_channel' => $data['payment_channel'],
        ];

        // dd($data);
        $this->paymentDetailRepository->store($record);

        //update order
        $table_stats = explode("-", $data["external_id"]);
        if ($table_stats[0] == "PRD") {
            //update produk order table
            $this->orderRepository->editByNota($data["external_id"], [
                'status' => $data['status'],
            ]);
        } else {
            // update hampers order table
            $this->orderRepository->editByNota($data["external_id"], [
                'status' => $data['status'],
            ]);
        }

        return response()->json(['success' => true]);
    }
}
