<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use App\Repositories\PaymentMethodRepository;
use Exception;
use Helper;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PaymentMethodController extends Controller
{

    private PaymentMethodRepository $repository;
    protected $data = array();

    public function __construct(PaymentMethodRepository $repository)
    {
        $this->repository = $repository;
        $this->data['title'] = 'Payment Method';
        $this->data['view_directory'] = "admin.feature.paymentmethod";
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
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
                $data = $this->repository->getAll();
            } catch (Exception $e) {
                dd($e);
            }
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<form method="POST" action="' . route('paymentmethod.destroy', $row["id"]) . '">
                                        ' . method_field("DELETE") . '
                                        ' . csrf_field() . '
                                        <a href="' . route("paymentmethod.edit", $row["id"]) . '" class="btn bg-gradient-info btn-tooltip"><i class="bi bi-pencil-square"></i></a>
                                        <button type="button" id="deleteRow" data-message="' . $row["holder_name"] . '" class="btn bg-gradient-danger btn-tooltip show-alert-delete-box" data-toggle="tooltip" title="Delete"><i class="bi bi-trash"></i></button>
                                    </form>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $ref = $this->data;
        $ref["url"] = route("paymentmethod.store");
        return view($this->data['view_directory'] . '.form', compact('ref'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            "holder_name" => ['required', 'string', 'max:50'],
            "name_bank" => ['required', 'string', 'max:50'],
            "rekening_number" => ['required', 'string', 'max:50'],
            "is_active" => ['required']
        ], [], [
            "holder_name" => "Nama Pemilik Rekening",
            "name_bank" => "Nama Bank",
            "rekening_number" => "Nomor Rekening",
            "is_active" => "Aktivasi Akun"
        ]);
        $data['created_by'] = auth()->user()->id;
        $data['id'] = 'PYM-' . Helper::table_id();
        try {
            $this->repository->store($data);
            return redirect()->route('paymentmethod.index')->with('success', 'Berhasi menambah data' . $data["holder_name"]);
        } catch (Exception $e) {
            if (env('APP_DEBUG')) {
                return $e->getMessage();
            }
            return back()->with('error', "Oops..!! Terjadi keesalahan saat menyimpan data")->withInput($request->input);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $ref = $this->data;
        $data = $this->repository->getById($id);
        $ref["url"] = route("paymentmethod.update", $id);
        return view($this->data['view_directory'] . '.form', compact('ref', "data"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            "holder_name" => ['required', 'string', 'max:50'],
            "name_bank" => ['required', 'string', 'max:50'],
            "rekening_number" => ['required', 'string', 'max:50'],
            "is_active" => ['required']
        ], [], [
            "holder_name" => "Nama Pemilik Rekening",
            "name_bank" => "Nama Bank",
            "rekening_number" => "Nomor Rekening",
            "is_active" => "Aktivasi Akun"
        ]);
        $data['updated_by'] = auth()->user()->id;
        try {
            $this->repository->edit($id, $data);
            return redirect()->route('paymentmethod.index')->with('success', 'Berhasi mengubah data ' . $data["holder_name"]);
        } catch (Exception $e) {
            if (env('APP_DEBUG')) {
                return $e->getMessage();
            }
            return back()->with('error', "Oops..!! Terjadi keesalahan saat menyimpan data")->withInput($request->input);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $this->repository->destroy($id);
            return back()->with('success', 'Data berhasil di hapus');
        } catch (Exception $e) {
            if (env('APP_DEBUG')) {
                return $e->getMessage();
            }
            return back()->with('error', "Oops..!! Terjadi keesalahan saat menghapus data");
        }
    }
}
