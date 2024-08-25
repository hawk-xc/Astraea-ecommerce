<?php

namespace App\Http\Controllers\Fo\payment;

use App\Repositories\ContactUsRepository;
use App\Repositories\AboutUsRepository;
use App\Repositories\PaymentDetailRepository;

use App\Repositories\OrderHampersRepository;
use App\Repositories\OrderHampersDetailRepository;
use App\Repositories\ShippingRepository;

use App\Repositories\DiscountCostumerRepository;

use App\Repositories\ProductRepository;

use App\Repositories\AppFeeRepository;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;

use Helper;


class PaymentOrderHampersController extends Controller
{
    private ContactUsRepository $contactUsRepository;
    private AboutUsRepository $aboutUsRepository;
    private PaymentDetailRepository $paymentDetailRepository;
    private OrderHampersRepository $orderRepository;
    private OrderHampersDetailRepository $orderDetailRepository;
    private ShippingRepository $shippingRepository;
    private DiscountCostumerRepository $d_customer_repository;
    private ProductRepository $productRepository;
    private AppFeeRepository $appFeeRepository;

    protected $data = array();
    public function __construct(ContactUsRepository $contactUsRepository, AboutUsRepository $aboutUsRepository, PaymentDetailRepository $paymentDetailRepository, OrderHampersRepository $orderRepository, OrderHampersDetailRepository $orderDetailRepository, ShippingRepository $shippingRepository, DiscountCostumerRepository $d_customer_repository, ProductRepository $productRepository, AppFeeRepository $appFeeRepository)
    {
        $this->contactUsRepository = $contactUsRepository;
        $this->aboutUsRepository = $aboutUsRepository;
        $this->paymentDetailRepository = $paymentDetailRepository;
        $this->orderRepository = $orderRepository;
        $this->orderDetailRepository = $orderDetailRepository;
        $this->shippingRepository = $shippingRepository;
        $this->d_customer_repository = $d_customer_repository;
        $this->productRepository = $productRepository;
        $this->appFeeRepository = $appFeeRepository;
        $this->data['title'] = 'Payment';
        $this->data['view_directory'] = "guest.feature.payment.hampers";
    }

    public function index(String $order)
    {
        try 
        {
            $no_nota = Crypt::decryptString($order);
        } catch (Illuminate\Contracts\Encryption\DecryptException $e) {
            $no_nota = null;
        }

        //data untuk ditampilkan
        $ref = $this->data;
        $data['contact'] = $this->contactUsRepository->getById('1');
        $data['about'] = $this->aboutUsRepository->getById('1');
        
        //order
        $data['order'] = $this->orderRepository->getByNota($no_nota);

        //jika sudah ada link pembayaran maka akan diarahkan
        if(isset($data['order']['payment_link']))
        {
            return redirect()->route('shop-hampers.index')->with('toast_success', 'Selamat berbelanja kembali');
        }
        
        //shiping
        $data['shipping'] = $this->shippingRepository->getById($data['order']['id']);
        $data['id_destination'] = auth()->guard('customer')->user()['district_id'];
        $data['addres'] = Auth()->guard('customer')->user()->address;

        //app fee
        $data['app_fee'] = $this->appFeeRepository->getById(1)['fee_amount'];
        
        //total price
        $data['total_price'] = ($data['order']['shipping']  + $data['app_fee']) + ( $data['order']['sub_total_price'] * (1 - $data['order']['discount_amount'] / 100));

        $data['total_price'] = Helper::to_rupiah($data['total_price']);
        $data['app_fee'] = Helper::to_rupiah($data['app_fee']);
        
        //variable yang digunakan selector
        $data['id_nota'] = $order;
        $data['id_order'] = Crypt::encryptString($data['order']['id']);
        
        return view($this->data['view_directory'] . '.index', compact('ref', 'data'));
    }
    public function create(Request $request,String $order)
    {
        try 
        {
            $no_nota = Crypt::decryptString($order);
        } catch (Illuminate\Contracts\Encryption\DecryptException $e) {
            $no_nota = null;
        }

        //data
        $data['order'] = $this->orderRepository->getByNota($no_nota);

        if(isset($data['order']['payment_link']))
        {
            return redirect($data['order']['payment_link']);
        }

        $data['shipping'] = $this->shippingRepository->getById($data['order']['id']);
        $data['discount'] = 0;
        $data['app_fee'] = $this->appFeeRepository->getById(1)['fee_amount'];

        //shipping checking
        if(!isset($data['shipping']['price']))
        {
            return redirect()->back()->with('toast_warning', 'Shipping belum dimasukkan');
        }
        //checking data discount
        $reccord['id_cutomer'] = Auth()->guard('customer')->user()->id;
        
        $coupon = $this->d_customer_repository->getByCodePromo($reccord['id_cutomer'], $data['order']['code_discount']);

        //menggabungkan date dan time
        $startDateTime = isset($coupon['discountData']['start_date']) ? Carbon::createFromFormat('Y-m-d H:i:s', $coupon['discountData']['start_date'] . ' ' . $coupon['discountData']['start_time']) : '';
        $endDateTime = isset($coupon['discountData']['end_date']) ?  Carbon::createFromFormat('Y-m-d H:i:s', $coupon['discountData']['end_date'] . ' ' . $coupon['discountData']['end_time']) : '';
        $currentDateTime = Carbon::now();

        //checking berlaku discount
        if(isset($coupon['discountData']['is_active']))
        {
            if(($startDateTime >= $currentDateTime) || ($endDateTime <= $currentDateTime)  || ($coupon['discountData']['is_active'] != '1'))
            {
                if($coupon['discountData']['id'] != 'DIS-20240000000000000001'){
                    return redirect()->back()->with('toast_warning', 'Coupon discount tidak tersedia');
                }
            }

            $data['discount'] = $coupon['discountData']['discount_amount'];
        }

        //checking data product
        $data['detail_order'] = $this->orderDetailRepository->orderList($data['order']['id']);
        foreach ($data['detail_order'] as $order_product) {
            if($order_product['hampers_data']['stock'] <  $order_product['quantity'])
            {
                return redirect()->back()->with('toast_warning', 'Product stok tidak tersedia');
            }
        }
        
        //validasi description
        $reccord = $request->validate([
            "description" => ['nullable', 'string', 'max:255'],
        ], [], [
            "description" => "Description",
        ]);

        $reccord['description'] = '" '. $reccord['description'] .' "';

        //menghitung total
        $total_price = ($data['shipping']['price'] + $data['app_fee']) + ( $data['order']['sub_total_price'] * (1 - $data['discount'] / 100));

        try {
            //membuat link
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ' . base64_encode(env('XENDIT_SECRET_KEY') . ':'),
            ])
            ->post('https://api.xendit.co/v2/invoices', [
                'external_id' => $no_nota,
                'amount' => $total_price,
                'payer_email' => auth()->guard('customer')->user()->email,
                'description' => $reccord['description'],
                'success_redirect_url'=> route('fo.home'),
            ]);

            //apply discount
            if(isset($coupon['discountData']['is_active']))
            {                
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
                $this->productRepository->edit($order_product['hampers_data']['id'], [
                    'stock' => $order_product['hampers_data']['stock'] - $order_product['quantity']
                ]);
            }

            // Mengambil respons dari API Xendit
            return redirect($response->json()['invoice_url']);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Terjadi Kesahalahan'. $e);
        }
    }

}
