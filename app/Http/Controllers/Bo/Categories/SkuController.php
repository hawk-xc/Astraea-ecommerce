<?php

namespace App\Http\Controllers\Bo\Categories;

use App\Http\Controllers\Controller;
use App\Repositories\SkuRepository;
use Exception;
use Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\Facades\DataTables;

class SkuController extends Controller
{

    private SkuRepository $repository;
    protected $data = array();

    public function __construct(SkuRepository $repository)
    {
        $this->repository = $repository;
        $this->data['title'] = 'SKU';
        $this->data['view_directory'] = "admin.feature.sku";
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
                    $btn = '<form method="POST" action="' . route('sku.destroy', encrypt($row["id"])) . '">
                                        ' . method_field("DELETE") . '
                                        ' . csrf_field() . '
                                        <a href="' . route("sku.edit", encrypt($row["id"])) . '" class="btn bg-gradient-info btn-tooltip"><i class="bi bi-pencil-square"></i></a>
                                        <button type="button" id="deleteRow" data-message="' . $row["name"] . '" class="btn bg-gradient-danger btn-tooltip show-alert-delete-box" data-toggle="tooltip" title="Delete"><i class="bi bi-trash"></i></button>
                                    </form>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ref = $this->data;
        return view($this->data['view_directory'] . '.index', compact('ref'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $ref = $this->data;
        $ref["url"] = route("sku.store");
        return view($this->data['view_directory'] . '.form', compact('ref'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            "code" => ['required', 'string', 'max:50'],
            "name" => ['required', 'string'],
        ], [], [
            "code" => "Nama Warna",
            "name" => "Deskripsi Warna"
        ]);
        $data['id'] = 'SKU-' . Helper::table_id();
        $data['created_by'] = auth()->user()->id;
        try {
            $this->repository->store($data);
            return redirect()->route('sku.index')->with('success', 'Berhasi menambah Warna ' . $data["name"]);
        } catch (Exception $e) {
            if (env('APP_DEBUG')) {
                return $e->getMessage();
            }
            return back()->with('error', "Oops..!! Terjadi keesalahan saat menyimpan data")->withInput($request->input);
        }
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
        $ref = $this->data;
        $data = $this->repository->getById(decrypt($id));
        $ref["url"] = route("sku.update", $id);
        // dd($data);
        return view($this->data['view_directory'] . '.form', compact('ref', "data"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            "code" => ['required', 'string', 'max:50'],
            "name" => ['required', 'string'],
        ], [], [
            "code" => "Kode SKU",
            "name" => "Nama SKU"
        ]);
        $data['updated_by'] = auth()->user()->id;
        try {
            $this->repository->edit(decrypt($id), $data);
            return redirect()->route('sku.index')->with('success', 'Berhasi mengubah SKU ' . $data["name"]);
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
        try {
            $this->repository->destroy(decrypt($id));
            return back()->with('success', 'Data berhasil di hapus');
        } catch (Exception $e) {
            if (env('APP_DEBUG')) {
                return $e->getMessage();
            }
            return back()->with('error', "Oops..!! Terjadi keesalahan saat menghapus data");
        }
    }

    public function sDatas(Request $request) {
        // $searchTerm = $request->input('q');
        $filteredProducts = $this->repository->getAll();
        $formattedData = $filteredProducts->map(function($category) {
            return [
                'id' => $category->id,
                'text' => $category->name
            ];
        });
        return response()->json(['results' => $formattedData]);
    }
}
