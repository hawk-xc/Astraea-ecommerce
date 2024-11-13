<?php

namespace App\Http\Controllers\Fo\cart;

use App\Repositories\ContactUsRepository;
use App\Repositories\CertificateRepository;
use App\Repositories\AboutUsRepository;
use App\Repositories\OrderRepository;
use App\Repositories\OrderDetailRepository;
use App\Repositories\ProductRepository;
use App\Repositories\ShippingRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PesananDetail;
use Carbon\Carbon;
use Exception;
use Helper;
use \App\Models\BannerView as BannerModel;
use Illuminate\Support\Facades\Crypt;
use App\Models\ProductColor;

class CartProductController extends Controller
{
    private OrderRepository $repository;
    private OrderDetailRepository $detail_repository;
    private ContactUsRepository $contactUsRepository;
    private CertificateRepository $certificateRepository;
    private AboutUsRepository $aboutUsRepository;
    private ProductRepository $productRepository;
    private ShippingRepository $shippingRepository;
    protected $data = array();

    public function __construct(OrderRepository $repository, OrderDetailRepository $detail_repository, ContactUsRepository $contactUsRepository, CertificateRepository $certificateRepository, AboutUsRepository $aboutUsRepository, ProductRepository $productRepository, ShippingRepository $shippingRepository)
    {
        $this->repository = $repository;
        $this->detail_repository = $detail_repository;
        $this->contactUsRepository = $contactUsRepository;
        $this->certificateRepository = $certificateRepository;
        $this->aboutUsRepository = $aboutUsRepository;
        $this->productRepository = $productRepository;
        $this->shippingRepository = $shippingRepository;
        $this->data['title'] = 'Cart Product';
        $this->data['view_directory'] = "guest.feature.cart.product";
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cart = $this->repository->cartCheckLogin();
        $ref = $this->data;
        $data['contact'] = $this->contactUsRepository->getById('1');
        $data['about'] = $this->aboutUsRepository->getById('1');
        $data['order'] = $this->repository->searchId($cart);
        $data['orders'] = $this->detail_repository->orderList($cart);

        $data['banner'] = BannerModel::first()->pluck('images');
        // dd($data);

        //lempar ke shop karena belum terisi
        if (!isset($data['order']['no_nota'])) {
            return redirect()->route('shop-product.index')->with('toast_success', 'Cart masih kosong silahkan pilih product terlebih dahulu');
        }

        $data['order']['no_nota'] = Crypt::encryptString($data['order']['no_nota']);
        $data['order']['id'] = Crypt::encryptString($data['order']['id']);

        return view($this->data['view_directory'] . '.index', compact('ref', 'data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $record = $request->validate([
            "quantity" => ['nullable', 'numeric'],
            "color" => ['required'],
        ]);

        $record['color'] = 'COL-' . $request->get('color');

        $quantity_product = isset($record['quantity']) ? $record['quantity'] : 1;

        $cart = $this->repository->cartCheckLogin();

        $waktuSekarang = Carbon::now()->toDateTimeString();
        // $data['product'] = $this->productRepository->getBySlugFo($slug);
        $data['product'] = $this->productRepository->getById(app('encrypter')->decrypt($id, false));
        
        $dtl_product = $this->detail_repository->getByIdProduct($data['product']['id'], $request->color, $cart);
        
        $stock = ProductColor::where('product_id', $data['product']['id'])->where('color_id', $request->color)->select('count')->first()->count;

        if ($stock <= 0) {
            // Jika stok kosong, alihkan ke halaman home dengan pesan error
            return redirect()->route('fo.home')->with('toast_warning', 'Maaf, stok sedang kosong.');
        }

        if(PesananDetail::where('order_id', 'CRP-20241112121343457306')->where('product_id', $data['product']['id'])->get()->count() > 0) {
            return back()->with('toast_warning', 'anda sudah menambahkan produk ke cart!');
        }

        // dd(PesananDetail::where('order_id', $this->repository->searchId($cart)['id']));

        if (isset($dtl_product['quantity'], $data['product']['stock'])) {
            if (($dtl_product['quantity'] + $quantity_product) > $data['product']['stock']) {
                return back()->with('toast_warning', 'produk sudah dicheckout!');
            }
        }

        try {
            // update
            //mendeteksi apakah cart sudah ada
            if ($this->repository->searchId($cart)) {
                //cart yang sudah ada
                $price_total = $this->repository->getById($cart)['sub_total_price'];

                //mendeteksi product dalam cart apakah sudah ada atau belum
                if (isset($dtl_product)) {
                    $quantity_product = $dtl_product['quantity'] + $quantity_product;
                    $add_price =  $quantity_product * $data['product']['price'];
                    $update_dtl = [
                        'quantity'        => $quantity_product,
                        'color'           => 'COL-' . $request->color,
                        'price'           => $data['product']['price'],
                        'sub_total_price' => $add_price,

                    ];
                    $this->detail_repository->updateDetailCart($dtl_product['id'], $update_dtl);
                } else {
                    //memasukkan produk yang belum ada dengan quantity satu
                    $add_price = $data['product']['price'];
                    // dd($quantity_product);
                    $order_detail = [
                        'id'        => 'DTL-CRP-' . Helper::table_id(),
                        'order_id'  => $cart,
                        'product_id' => $data['product']['id'],
                        'quantity'  => $quantity_product,
                        'color'     => 'COL-' . $request->color,
                        'price'     => $add_price,
                        'sub_total_price' => $data['product']['price'] * $quantity_product,
                        'created_by' => 'pelanggan',
                    ];
                    \App\Models\PesananDetail::create($order_detail);
                }
                //menghitung sub total
                $sub_total = $this->detail_repository->subTotalOrder($cart);
                //menghapus  data shipping atau ongkir pengiriman
                $this->shippingRepository->destroy($cart);
                //mengupdate cart
                $this->repository->edit($cart, [
                    'shipping' => 0,
                    'shipping_status' => 'PENDING',
                    'sub_total_price' => $sub_total
                ]);
            } else {
                $order_data = [
                    'id'          => $cart,
                    'order_date'  => $waktuSekarang,
                    'status'      => 'PENDING',
                    'no_nota'     => 'PRD-' . Helper::table_id(),
                    'shipping'    => 0,
                    'shipping_status' => 'PENDING',
                    'sub_total_price' => $data['product']['price'],
                    'address'     => '',
                    'created_by'  => 'pelanggan',
                    'updated_by'  => 'pelanggan',
                ];
                if (isset(Auth()->guard('customer')->user()->id)) {
                    $order_data['costumer_id'] = Auth()->guard('customer')->user()->id;
                }
                $this->repository->store($order_data);

                $order_detail = [
                    'id'        => 'DTL-CRP-' . Helper::table_id(),
                    'order_id'  => $cart,
                    'product_id' => $data['product']['id'],
                    'quantity'  => $quantity_product,
                    'color'     => $request->color,
                    'price'     => $data['product']['price'],
                    'sub_total_price' => $data['product']['price'],
                    'created_by' => 'pelanggan',
                ];
                $this->detail_repository->store($order_detail);
            }
            // return back()->with('toast_success', 'Berhasil menambah produk');
            return redirect()->route('fo.cart-product.index')->with('toast_success', 'Berhasil menambah produk');
        } catch (Exception $e) {
            if (env('APP_DEBUG')) {
                return $e->getMessage();
            }
            return back()->with('toast_warning', "Oops..!! Terjadi keesalahan saat menyimpan data")->withInput($request->input);
        }
    }
    // {
    //     dd($request);
    // }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $dtl_product = $this->detail_repository->getById($id);
        $order_id = $dtl_product['order_id'];
        try {
            $this->detail_repository->destroy($id);

            $price_total = $this->detail_repository->subTotalOrder($order_id);

            $this->repository->edit($order_id, ['sub_total_price' => $price_total]);
            return back()->with('toast_success', 'Berhasil menghapus product');
        } catch (Exception $e) {
            if (env('APP_DEBUG')) {
                return $e->getMessage();
            }
            return back()->with('toast_warning', "Oops..!! Terjadi keesalahan saat menyimpan data")->withInput($request->input);
        }
    }

    public function updateQuantity(Request $request)
    {
        $orderDetailId = $request->input('orderDetailId');
        $quantity = $request->input('quantity');

        $dtl_order = $this->detail_repository->orderDtll($orderDetailId);
        //check stock dan harga
        $price = $dtl_order['product_data']['price'];
        $price = \App\Models\ProductColor::where('product_id', $dtl_order['product_id'])->where('color_id', $dtl_order['color']['id'])->value('count');
        //masukkan price

        $order = true;

        $cekjumlah = \App\Models\ProductColor::where('product_id', $dtl_order['product_id'])->where('color_id', str_replace("COL-", "", $dtl_order['color']['id']))->value('count');

        // return response()->json(['success' => false, 'message' => $cekjumlah], 404);

        if ($quantity <= $cekjumlah) {
            //add detail produk
            $add_price =  $quantity * $dtl_order['product_data']['price'];
            $update_dtl = [
                'quantity'        => $quantity,
                'price'           => $dtl_order['product_data']['price'],
                'sub_total_price' => $add_price,
            ];
            $this->detail_repository->updateDetailCart($orderDetailId, $update_dtl);

            //add ke keranjang

            //menghitung sub total
            $sub_total = $this->detail_repository->subTotalOrder($dtl_order['order_id']);
            //menghapus  data shipping atau ongkir pengiriman
            $this->shippingRepository->destroy($dtl_order['order_id']);
            //mengupdate cart
            $this->repository->edit($dtl_order['order_id'], [
                'shipping' => 0,
                'shipping_status' => 'PENDING',
                'sub_total_price' => $sub_total
            ]);

            return response()->json([
                'price' => Helper::to_rupiah($dtl_order['product_data']['price']),
                'price_sub_total_product' => Helper::to_rupiah($add_price),
                'price_sub_total' => Helper::to_rupiah($sub_total),
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Kuantitas melebihi stock',
                'available_quantity' => $cekjumlah // Tambahkan nilai ini untuk dikirim ke JavaScript
            ], 404);
        }
    }
}
