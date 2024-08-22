<?php

namespace App\Http\Controllers\Bo\Categories;

use App\Repositories\SubCategoriesRepository;
use Exception;
use Helper;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;

class SubCategoriesController extends Controller
{
    private SubCategoriesRepository $repository;
    protected $data = array();

    public function __construct(SubCategoriesRepository $repository)
    {
        $this->repository = $repository;
        $this->data['title'] = 'sub kategori';
        $this->data['view_directory'] = "admin.feature.categories.subcategories";
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // dd($this->repository->getAll());
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
                    $btn = '<form method="POST" action="' . route('subcategory.destroy', $row["id"]) . '">
                                        ' . method_field("DELETE") . '
                                        ' . csrf_field() . '
                                        <a href="' . route("subcategory.edit", $row["id"]) . '" class="btn bg-gradient-info btn-tooltip"><i class="bi bi-pencil-square"></i></a>
                                        <button type="button" id="deleteRow" data-message="' . $row["name"] . '" class="btn bg-gradient-danger btn-tooltip show-alert-delete-box" data-toggle="tooltip" title="Delete"><i class="bi bi-trash"></i></button>
                                    </form>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function cate(Request $request)
    {
        $categoryId = $request->input('category_id');
        $filteredProducts = $this->repository->getCate($categoryId);
        $formattedData = $filteredProducts->map(function($subCategory) {
            return [
                'id' => $subCategory->id,
                'text' => $subCategory->name
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
        $ref["url"] = route("subcategory.store");
        return view($this->data['view_directory'] . '.form', compact('ref'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            "name" => ['required', 'string', 'max:50'],
            "id_category" => ['required'],
            "description" => ['required', 'string'],
        ], [], [
            "name" => "Nama Kategori",
            "id_category" => "Kategori",
            "description" => "Deskripsi kategori"
        ]);
        $data['id'] = 'SCT-' . Helper::table_id();
        $data['created_by'] = auth()->user()->id;
        try {
            $this->repository->store($data);
            return redirect()->route('subcategory.index')->with('success', 'Berhasi menambah kategori ' . $data["name"]);
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
        $data = $this->repository->getById($id)->toArray();
        $id = Crypt::encryptString($id);
        $ref["url"] = route("subcategory.update", $id);
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
            "id_category" => ['required'],
            "description" => ['required', 'string'],
        ], [], [
            "name" => "Nama Kategori",
            "id_category" => "Kategori",
            "description" => "Deskripsi kategori"
        ]);
        $data['updated_by'] = auth()->user()->id;
        try {
            $this->repository->edit($id, $data);
            return redirect()->route('subcategory.index')->with('success', 'Berhasi mengubah kategori ' . $data["name"]);
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
