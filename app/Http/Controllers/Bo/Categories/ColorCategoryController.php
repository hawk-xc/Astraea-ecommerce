<?php

namespace App\Http\Controllers\Bo\Categories;

use App\Repositories\ColorRepository;
use Exception;
use Helper;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;

class ColorCategoryController extends Controller
{
    private ColorRepository $repository;
    protected $data = array();

    public function __construct(ColorRepository $repository)
    {
        $this->repository = $repository;
        $this->data['title'] = 'Kategori Warna';
        $this->data['view_directory'] = "admin.feature.categories.color";
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
                $data = $this->repository->getAll();
            } catch (Exception $e) {
                dd($e);
            }
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $row["id"] = Crypt::encryptString($row["id"]);
                    $btn = '<form method="POST" action="' . route('color.destroy', $row["id"]) . '">
                                        ' . method_field("DELETE") . '
                                        ' . csrf_field() . '
                                        <a href="' . route("color.edit", $row["id"]) . '" class="btn bg-gradient-info btn-tooltip"><i class="bi bi-pencil-square"></i></a>
                                        <button type="button" id="deleteRow" data-message="' . $row["name"] . '" class="btn bg-gradient-danger btn-tooltip show-alert-delete-box" data-toggle="tooltip" title="Delete"><i class="bi bi-trash"></i></button>
                                    </form>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
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
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $ref = $this->data;
        $ref["url"] = route("color.store");
        return view($this->data['view_directory'] . '.form', compact('ref'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            "name" => ['required', 'string', 'max:50'],
            "description" => ['required', 'string'],
        ], [], [
            "name" => "Nama Warna",
            "description" => "Deskripsi Warna"
        ]);
        $data['id'] = 'COL-' . Helper::table_id();
        $data['created_by'] = auth()->user()->id;
        try {
            $this->repository->store($data);
            return redirect()->route('color.index')->with('success', 'Berhasi menambah Warna ' . $data["name"]);
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
        $id = Crypt::decryptString($id);
        $ref = $this->data;
        $data = $this->repository->getById($id);
        $id = Crypt::encryptString($id);
        $ref["url"] = route("color.update", $id);
        return view($this->data['view_directory'] . '.form', compact('ref', "data"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $id = Crypt::decryptString($id);
        $data = $request->validate([
            "name" => ['required', 'string', 'max:50'],
            "description" => ['required', 'string'],
        ], [], [
            "name" => "Nama Warna",
            "description" => "Deskripsi Warna"
        ]);
        $data['updated_by'] = auth()->user()->id;
        try {
            $this->repository->edit($id, $data);
            return redirect()->route('color.index')->with('success', 'Berhasi mengubah Warna ' . $data["name"]);
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
        $id = Crypt::decryptString($id);
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
