<?php

namespace App\Http\Controllers\Bo\Order;

use App\Repositories\OrderRepository;
use App\Repositories\OrderDetailRepository;
use App\Repositories\ShippingRepository;
use App\Repositories\ContactUsRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Helper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;

class ManagementOrderController extends Controller
{
    private OrderRepository $repository;
    private OrderDetailRepository $detail_repository;
    private ShippingRepository $shippingRepository;
    private ContactUsRepository $contactUsRepository;
    protected $data = array();

    public function __construct(OrderRepository $repository, OrderDetailRepository $detail_repository, ShippingRepository $shippingRepository, ContactUsRepository $contactUsRepository)
    {
        $this->repository = $repository;
        $this->detail_repository = $detail_repository;
        $this->shippingRepository = $shippingRepository;
        $this->contactUsRepository = $contactUsRepository;
        $this->data['title'] = 'Pesanan';
        $this->data['view_directory'] = "admin.feature.order.product";
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ref = $this->data;
        return view($this->data['view_directory'] . '.index', compact('ref'));
    }

    public function datas(Request $request)
    {
        if ($request->ajax()) {
            try {
                $data = $this->repository->getAllnotPending();
            } catch (Exception $e) {
                dd($e);
            }
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn(
                    "total_price",
                    function ($inquiry) {
                        return $inquiry->fTPrice();
                    }
                )
                ->addColumn('action', function ($row) {
                    $row["id"] = Crypt::encryptString($row["id"]);
                    $btn = '<a href="'. route("order_product.show", $row["id"]) .'" class ="btn bg-gradient-primary btn-tooltip mx-1">
                                <i class="bi bi-eye-fill"></i>
                            </a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
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
        $id = Crypt::decryptString($id);
        $ref = $this->data;
        $data = $this->repository->getByIdDtlOrder($id)->toArray();
        $data['sub_total_price'] = Helper::to_rupiah($data['sub_total_price']);
        $data['app_admin'] =  Helper::to_rupiah($data['app_admin']);
        $data['shipping'] =  Helper::to_rupiah($data['shipping']);
        $data['total_price'] =  Helper::to_rupiah($data['total_price']);
        $data['order_date'] = Carbon::parse($data['order_date'])->isoFormat('D MMMM YYYY');
        $data['id'] = Crypt::encryptString($id);
        return view($this->data['view_directory'] . '.detail', compact('ref', 'data'));
    }

    public function detail(Request $request, string $id)
    {
        $id = Crypt::decryptString($id);
        if ($request->ajax()) {
            try {
                $data = $this->detail_repository->getDetail($id);
            } catch (Exception $e) {
                dd($e);
            }
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn(
                    "price",
                    function ($inquiry) {
                        return Helper::to_rupiah($inquiry["price"]);
                    }
                )
                ->editColumn(
                    "sub_total_price",
                    function ($inquiry) {
                        return Helper::to_rupiah($inquiry["sub_total_price"]);
                    }
                )
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $id = Crypt::decryptString($id);
        $ref = $this->data;
        $data = $this->repository->getByIdDtlOrder($id)->toArray();
        $data['shipping'] =  Helper::to_rupiah($data['shipping']);
        $data['id'] = Crypt::encryptString($id);
        $ref['url'] = route('order_product.update', $data['id']);
        return view($this->data['view_directory'] . '.form', compact('ref', 'data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $id = Crypt::decryptString($id);

        $data = $request->validate([
            "shipping_status" => ['required', 'string', 'max:50'],
        ], [], [
            "shipping_status" => "Status Pengiriman",
        ]);

        $data['updated_by'] = auth()->user()->id;

        try {
            $this->repository->edit($id, $data);
            $id = Crypt::encryptString($id);
            return redirect()->route('order_product.show', $id)->with('success', 'Berhasi mengubah Status Pengiriman');
        } catch (Exception $e) {
            if (env('APP_DEBUG')) {
                return $e->getMessage();
            }
            return back()->with('error', "Oops..!! Terjadi keesalahan saat menyimpan data")->withInput($request->input);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function printresi(string $id)
    {
        $id = Crypt::decryptString($id);
        $ref = $this->data;
        $data = $this->repository->getByIdDtlOrder($id)->toArray();
        $data['shipping'] =  Helper::to_rupiah($data['shipping']);
        $data['contact'] = $this->contactUsRepository->getById('1');
        $data['product'] = $this->detail_repository->getDetail($id);
        $print = view($this->data['view_directory'] . '.print', compact('data'))->render();
        $pdf = Pdf::loadHTML($print);
        return $pdf->stream();
    }
}
