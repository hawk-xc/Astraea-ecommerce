<?php

namespace App\Http\Controllers\Bo\Categories;

use App\Repositories\CategoriesRepository;
use Exception;
use Helper;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\Controller;

class CategoriesController extends Controller
{

    private CategoriesRepository $repository;
    protected $data = array();

    public function __construct(CategoriesRepository $repository)
    {
        $this->repository = $repository;
        $this->data['title'] = 'kategori';
        $this->data['view_directory'] = "admin.feature.categories.categories";
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

    /**
     * Display a listing for datatable.
     *
     * @return \Illuminate\Http\Response
     */
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
                    $btn = '<form method="POST" action="' . route('category.destroy', $row["id"]) . '">
                                        ' . method_field("DELETE") . '
                                        ' . csrf_field() . '
                                        <a href="' . route("category.edit", $row["id"]) . '" class="btn bg-gradient-info btn-tooltip"><i class="bi bi-pencil-square"></i></a>
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
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $ref = $this->data;
        $ref["url"] = route("category.store");
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
            "name" => ['required', 'string', 'max:50'],
            "description" => ['required', 'string'],
        ], [], [
            "name" => "Nama Kategori",
            "description" => "Deskripsi kategori"
        ]);
        $data['id'] = 'CAT-' . Helper::table_id();
        $data['created_by'] = auth()->user()->id;
        try {
            $this->repository->store($data);
            return redirect()->route('category.index')->with('success', 'Berhasi menambah kategori ' . $data["name"]);
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
     * @param  \App\Models\Categories  $categories
     * @return \Illuminate\Http\Response
     */
    public function show(Categories $categories)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Categories  $categories
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $id = Crypt::decryptString($id);
        $ref = $this->data;
        $data = $this->repository->getById($id);
        $id = Crypt::encryptString($id);
        $ref["url"] = route("category.update", $id);
        return view($this->data['view_directory'] . '.form', compact('ref', "data"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Categories  $categories
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $id = Crypt::decryptString($id);
        $data = $request->validate([
            "name" => ['required', 'string', 'max:50'],
            "description" => ['required', 'string'],
        ], [], [
            "name" => "Nama Kategori",
            "description" => "Deskripsi kategori"
        ]);
        $data['updated_by'] = auth()->user()->id;
        try {
            $this->repository->edit($id, $data);
            return redirect()->route('category.index')->with('success', 'Berhasi mengubah kategori ' . $data["name"]);
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
     * @param  \App\Models\Categories  $categories
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
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
