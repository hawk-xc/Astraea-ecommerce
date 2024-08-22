<?php

namespace App\Http\Controllers\Fo\shipping;

use App\Repositories\ContactUsRepository;
use App\Repositories\AboutUsRepository;

use App\Repositories\OrderRepository;
use App\Repositories\OrderDetailRepository;
use App\Repositories\ShippingRepository;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;



class ShippingProductController extends Controller
{
    private ContactUsRepository $contactUsRepository;
    private AboutUsRepository $aboutUsRepository;
    private OrderRepository $orderRepository;
    private OrderDetailRepository $orderDetailRepository;
    private ShippingRepository $shippingRepository;

    protected $data = array();
    public function __construct(ContactUsRepository $contactUsRepository, AboutUsRepository $aboutUsRepository, OrderRepository $orderRepository, OrderDetailRepository $orderDetailRepository, ShippingRepository $shippingRepository)
    {
        $this->contactUsRepository = $contactUsRepository;
        $this->aboutUsRepository = $aboutUsRepository;
        $this->orderRepository = $orderRepository;
        $this->orderDetailRepository = $orderDetailRepository;
        $this->shippingRepository = $shippingRepository;
        $this->data['title'] = 'Shipping';
        $this->data['view_directory'] = "guest.feature.shipping.product";
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {

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
        // $ref = $this->data;
        // $data['contact'] = $this->contactUsRepository->getById('1');
        // $data['about'] = $this->aboutUsRepository->getById('1');
        // $data['order'] = $id;
        // return view($this->data['view_directory'] . '.index', compact('ref', 'data'));
    }

    public function storeCek(Request $request, string $id)
    {
        try
        {
            $no_nota = Crypt::decryptString($id);
        } catch (Illuminate\Contracts\Encryption\DecryptException $e) {
            $no_nota = null;
        }

        $idOrder = $this->orderRepository->getByNota($no_nota)['id'];
        $eIdOrder = Crypt::encryptString($idOrder);


         $record = $request->validate([
            'district_id' => 'required|exists:districts,id', // Asumsikan ada tabel districts
            'expedisi' => 'required|in:jne,pos,tiki',
            'address' => 'required|string|max:255',
        ]);

        //menghitung jumlah berat
         $totalWeight = $this->orderDetailRepository->sumWeight($idOrder)
                ->reduce(function ($carry, $detail) {
                    return $carry + ($detail->quantity * $detail->productData->weight);
                }, 0);

        try {
            //update order
            $this->orderRepository->edit($idOrder, [
                'address' => $record['address'],
                'shipping' => 0,
                'shipping_status' => 'PENDING'
            ]);
            //store shiping
            $this->shippingRepository->destroy($idOrder);
            $this->shippingRepository->store([
                'id_order'  => $idOrder,
                'name'      =>  $record['expedisi'],
                'destination'  => $record['district_id'],
                'weight'    => $totalWeight,
                ]);
            //redirect
            return redirect()->route('fo.shipping-product.edit', $eIdOrder);

        } catch (Exception $e) {

        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $ref = $this->data;
        $data['contact'] = $this->contactUsRepository->getById('1');
        $data['about']  = $this->aboutUsRepository->getById('1');
        $id             = Crypt::decryptString($id);
        $data['id_order'] = Crypt::encryptString($id);

        $data_layanan = $this->shippingRepository->getById($id);
        $apiKey = env('RAJA_ONGKIR_API_KEY'); // Ganti dengan API key Anda dari Raja Ongkir

        $origin = $data['contact']['id_distric'];
        $destination = $data_layanan['destination'];
        $weight = $data_layanan['weight'];
        $courier = $data_layanan['name'];

        $response = Http::asForm()->post('https://api.rajaongkir.com/starter/cost', [
            'key' => $apiKey,
            'origin' => $origin,
            'destination' => $destination,
            'weight' => $weight,
            'courier' => $courier,
        ]);
        // dd($response->json());
        $data['rajaongkir'] = $response->json()['rajaongkir']['results'];
        return view($this->data['view_directory'] . '.form', compact('ref', 'data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $id = Crypt::decryptString($id);

        $record = $request->validate([
            'ongkir' => 'required',
        ]);
        try {
            $data_layanan = $this->shippingRepository->getById($id);
            $apiKey = env('RAJAONGKIR_API_KEY'); // Ganti dengan API key Anda dari Raja Ongkir
            $data['contact'] = $this->contactUsRepository->getById('1');
            //mengambil nilai contact
            $origin = $data['contact']['id_distric'];
            $destination = $data_layanan['destination'];
            $weight = $data_layanan['weight'];
            $courier = $data_layanan['name'];
            $apiKey = env('RAJA_ONGKIR_API_KEY'); // Ganti dengan API key Anda dari Raja Ongkir
            $response = Http::asForm()->post('https://api.rajaongkir.com/starter/cost', [
                'key' => $apiKey,
                'origin' => $origin,
                'destination' => $destination,
                'weight' => $weight,
                'courier' => $courier,
            ]);
            // dd($response->json());
            $data['rajaongkir'] = $response->json()['rajaongkir']['results'];

            // dd($record['ongkir']);
            $data_ongkir = $data['rajaongkir'][0]['costs'][$record['ongkir']];
            $input = [
                'service'   => $data_ongkir['service'] .' ('.  $data_ongkir['description'] .')',
                'price'     => $data_ongkir['cost'][0]['value'],
                'days'      => $data_ongkir['cost'][0]['etd']
            ];
            //update shiping
            $this->orderRepository->edit($id, [
                    'shipping' => $input['price'],
                    'shipping_status' => 'PENDING',
                ]);
            $this->shippingRepository->edit($id, $input);
            //redirect dan melempar no nota
            $nota = $this->orderRepository->searchId($id)['no_nota'];
            $nota = Crypt::encryptString($nota);
            return redirect()->route('product-payment', $nota);

        } catch (Exception $e) {

        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
