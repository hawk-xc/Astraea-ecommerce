<?php

namespace App\Http\Controllers\Fo\cart;

use App\Repositories\ContactUsRepository;
use App\Repositories\CertificateRepository;
use App\Repositories\AboutUsRepository;
use App\Repositories\OrderHampersRepository;
use App\Repositories\OrderHampersDetailRepository;
use App\Repositories\HampersProductRepository;
use App\Repositories\ShippingRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Helper;
use Illuminate\Support\Facades\Crypt;

class CartHampersController extends Controller
{
    private OrderHampersRepository $repository;
    private OrderHampersDetailRepository $detail_repository;
    private ContactUsRepository $contactUsRepository;
    private CertificateRepository $certificateRepository;
    private AboutUsRepository $aboutUsRepository;
    private HampersProductRepository $productRepository;
    private ShippingRepository $shippingRepository;
    protected $data = array();

    public function __construct(OrderHampersRepository $repository, OrderHampersDetailRepository $detail_repository, ContactUsRepository $contactUsRepository, CertificateRepository $certificateRepository, AboutUsRepository $aboutUsRepository, HampersProductRepository $productRepository, ShippingRepository $shippingRepository)
    {
        $this->repository = $repository;
        $this->detail_repository = $detail_repository;
        $this->contactUsRepository = $contactUsRepository;
        $this->certificateRepository = $certificateRepository;
        $this->aboutUsRepository = $aboutUsRepository;
        $this->productRepository = $productRepository;
        $this->shippingRepository = $shippingRepository;
        $this->data['title'] = 'Cart Hampers';
        $this->data['view_directory'] = "guest.feature.cart.hampres";


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
        
        //lempar ke shop karena belum terisi
        if(!isset($data['order']['no_nota']))
        {
            return redirect()->route('shop-hampers.index')->with('toast_success', 'Cart masih kosong silahkan pilih product terlebih dahulu');
        }

        $data['order']['no_nota'] = Crypt::encryptString($data['order']['no_nota']);
        $data['order']['id'] = Crypt::encryptString($data['order']['id']);

        return view($this->data['view_directory'] . '.index', compact('ref', 'data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $slug)
    {
        $record = $request->validate([
            "quantity" => ['nullable', 'numeric'],
        ], [], [
            "quantity" => "Quantity",
        ]);

        $quantity_product = isset($record['quantity']) ? $record['quantity'] : 1;


        $cart = $this->repository->cartCheckLogin();
        $waktuSekarang = Carbon::now()->toDateTimeString();
        $data['product'] = $this->productRepository->getBySlugFo($slug);

        $dtl_product = $this->detail_repository->getByIdProduct($data['product']['id'], $cart);

        // dd($dtl_product);

        if (isset($dtl_product['quantity'], $data['product']['stock'])) 
        {
            if (($dtl_product['quantity'] + $quantity_product) > $data['product']['stock']) 
            {
                return back()->with('toast_warning', 'stok tidak mencukupi');
            }
        }

        try {
            //mendeteksi apakah cart sudah ada
            if($this->repository->searchId($cart))
            {
                //cart yang sudah ada
                $price_total = $this->repository->getById($cart)['sub_total_price'];
                //mendeteksi product dalam cart apakah sudah ada atau belum
                if(isset($dtl_product))
                {
                    $quantity_product = $dtl_product['quantity'] + $quantity_product;
                    $add_price =  $quantity_product * $data['product']['price'];
                    $update_dtl = [
                        'quantity'        => $quantity_product,
                        'price'           => $data['product']['price'],
                        'sub_total_price' => $add_price,
                        ];
                    $this->detail_repository->updateDetailCart($dtl_product['id'], $update_dtl);
                }
                else
                {
                    //memasukkan produk yang belum ada dengan quantity satu
                    $add_price = $data['product']['price'];
                    $order_detail = [
                        'id'        => 'DTL-CRH-' . Helper::table_id(),
                        'order_id'  => $cart,
                        'hampers_id'=> $data['product']['id'],
                        'quantity'  => $quantity_product,
                        'price'     => $add_price,
                        'sub_total_price' => $data['product']['price'],
                        'created_by' => 'pelanggan',
                     ];
                     $this->detail_repository->store($order_detail);
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
            }
            else
            {
                $order_data = [
                    'id'          => $cart,
                    'order_date'  => $waktuSekarang,
                    'status'      => 'PENDING',
                    'category'  => 'NORMAL',
                    'no_nota'     => 'HPR-' . Helper::table_id(),
                    'shipping'    => 0,
                    'shipping_status' => 'PENDING',
                    'sub_total_price' => $data['product']['price'],
                    'address'     => '',
                    'created_by'  => 'pelanggan',
                    'updated_by'  => 'pelanggan',
                    ];
                if(isset(Auth()->guard('customer')->user()->id))
                {
                        $order_data['costumer_id'] = Auth()->guard('customer')->user()->id;
                }
                $this->repository->store($order_data);
                 
                 $order_detail = [
                    'id'        => 'DTL-CRH-' . Helper::table_id(),
                    'order_id'  => $cart,
                    'hampers_id'=> $data['product']['id'],
                    'quantity'  => $quantity_product,
                    'price'     => $data['product']['price'],
                    'sub_total_price' => $data['product']['price'],
                    'created_by' => 'pelanggan',
                 ];
                 $this->detail_repository->store($order_detail);
            }
            return back()->with('toast_success', 'Berhasil menambah produk');
        } catch (Exception $e) {
            if (env('APP_DEBUG')) {
                return $e->getMessage();
            }
            return back()->with('toast_warning', "Oops..!! Terjadi keesalahan saat menyimpan data")->withInput($request->input);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
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
         $price = $dtl_order['hampers_data']['price'];
         $price = $dtl_order['hampers_data']['stock'];
        //masukkan price

        $order = true;

        if ($quantity <= $dtl_order['hampers_data']['stock']) {
            //add detail produk
            $add_price =  $quantity * $dtl_order['hampers_data']['price'];
            $update_dtl = [
                'quantity'        => $quantity,
                'price'           => $dtl_order['hampers_data']['price'],
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
                    'price' => Helper::to_rupiah($dtl_order['hampers_data']['price']),
                    'price_sub_total_product' => Helper::to_rupiah($add_price),
                    'price_sub_total' => Helper::to_rupiah($sub_total),
                    'success' => true
                ]);
            
        } else {
            return response()->json(['success' => false, 'message' => 'Order not found'], 404);
        }
    }
}
