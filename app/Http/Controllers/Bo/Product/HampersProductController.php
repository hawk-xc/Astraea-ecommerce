<?php

namespace App\Http\Controllers\Bo\Product;

use App\Repositories\HampersProductRepository;
use App\Repositories\HampersImageProductRepository;
use Exception;
use Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;

class HampersProductController extends Controller
{
    private HampersProductRepository $repository;
    private HampersImageProductRepository $images_repository;

    protected $data = array();

    public function __construct(HampersProductRepository $repository, HampersImageProductRepository $images_repository)
    {
        $this->repository = $repository;
        $this->images_repository = $images_repository;
        $this->data['title'] = 'hampers produk';
        $this->data['view_directory'] = "admin.feature.products.hampers";
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
                ->editColumn(
                    "price",
                    function ($inquiry) {
                        $r_price = $inquiry["hpp"] / (1 - ( $inquiry["margin"] / 100 + $inquiry["b_layanan"] / 100));
                        $res_price = '';
                        if ($r_price > $inquiry["price"])
                        {
                            $res_price = ' <span class="badge bg-danger">Update Harga</span>';
                        }
                        return Helper::to_rupiah($inquiry["price"]) . $res_price;
                    }
                )
                ->editColumn(
                    "stock",
                    function ($inquiry) {
                        return $inquiry["stock"] . " Pcs";
                    }
                )
                ->addColumn('action', function ($row) {
                    $row["id"] = Crypt::encryptString($row["id"]);
                    $btn = '<form method="POST" action="' . route('hampers.destroy', $row["id"]) . '">
                                        ' . method_field("DELETE") . '
                                        ' . csrf_field() . '
                                        <a href="' . route("hampers.edit", $row["id"]) . '" class="btn bg-gradient-info btn-tooltip"><i class="bi bi-pencil-square"></i></a>
                                        <button type="button" id="deleteRow" data-message="' . $row["name"] . '" class="btn bg-gradient-danger btn-tooltip show-alert-delete-box" data-toggle="tooltip" title="Delete"><i class="bi bi-trash"></i></button>
                                    </form>';
                    return $btn;
                })
                ->rawColumns(['action', 'price'])
                ->make(true);
        }
    }

    public function sDatas(Request $request) {
        $searchTerm = $request->input('q');
        $filteredProducts = $this->repository->getSearch($searchTerm);
        $formattedData = $filteredProducts->map(function($product) {
            return [
                'id' => $product->id,
                'text' => $product->name
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
        $ref["url"] = route("hampers.store");
        return view($this->data['view_directory'] . '.form', compact('ref'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            "name" => ['required', 'string', 'max:100'],
            "price" => ['required', 'string', 'max:25'],
            "color" => ['required', 'string', 'max:250'],
            "hpp" => ['required', 'string', 'max:25'],
            "margin" => ['required', 'string', 'max:3'],
            "b_layanan" => ['required', 'string', 'max:3'],
            "stock" => ['required', 'string', 'max:4'],
            "category_id" => ['required', 'string', 'max:250'],
            "subcategory_id" => ['required', 'string', 'max:250'],
            "weight" => ['required', 'string', 'max:6'],
            "description" => ['required', 'string'],
        ],[],[
            "name" => "Nama produk",
            "price" => "Harga jual produk",
            "color" => "Warna barang",
            "stock" => "Jumlah barang",
            "weight" => "Berat barang",
            "margin" => "Margin barang",
            "b_layanan" => "Biaya layanan barang",
            "hpp" => "Harga Pokok Penjualan barang",
            "category_id" => "Kategori barang",
            "subcategory_id" => "Subkategori barang",
            "description" => "Deskripsi barang",
        ]);

        $request->validate([
            'front' => ['nullable', 'mimes:png,jpg,jpeg', 'max:5120'],
            'back' => ['nullable', 'mimes:png,jpg,jpeg', 'max:5120'],
            'left' => ['nullable', 'mimes:png,jpg,jpeg', 'max:5120'],
            'right' => ['nullable', 'mimes:png,jpg,jpeg', 'max:5120'],
            'detail1' => ['nullable', 'mimes:png,jpg,jpeg', 'max:5120'],
            'detail2' => ['nullable', 'mimes:png,jpg,jpeg', 'max:5120'],
            'detail3' => ['nullable', 'mimes:png,jpg,jpeg', 'max:5120'],
            'detail4' => ['nullable', 'mimes:png,jpg,jpeg', 'max:5120'],
        ],[],[
            'front' => "Gambar Depan",
            'back' => "Gambar Belakang",
            'left' => "Gambar Kanan",
            'right' => "Gambar Kiri",
            'detail1' => "Gambar Detail 1",
            'detail2' => "Gambar Detail 2",
            'detail3' => "Gambar Detail 3",
            'detail4' => "Gambar detail 4",
        ]);

        try
        {
            $data['id'] = 'HPR-' . Helper::table_id();
            $data['created_by'] = auth()->user()->id;
            $data['updated_by'] = auth()->user()->id;
            $data['slug'] = $this->repository->sluggable($data['name']);
            $this->repository->store($data);
            foreach ($request->file() as $key => $img) {
                    $image_path = $request->file($key)->store('images', 'public');
                    //save image
                    $image_record['id'] = 'IHS-' . Helper::table_id();
                    $image_record["product_id"] = $data['id'];
                    $image_record["position"] = $key;
                    $image_record["name"] = $image_path;
                    $image_record['created_by'] = auth()->user()->id;
                    $image_record['updated_by'] = auth()->user()->id;
                    //proses save
                    $this->images_repository->store($image_record);
            }
             return redirect()->route('hampers.index')->with('success', 'Berhasil menambah produk ' . $data["name"]);
        }
        catch (Exception $e)
        {
            if (env('APP_DEBUG'))
            {
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
        $data_gambar = $this->images_repository->getByIdProduct($id);
        $id = Crypt::encryptString($id);
        $ref["url"] = route("hampers.update", $id);
        return view($this->data['view_directory'] . '.form', compact('ref', "data", 'data_gambar'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $id = Crypt::decryptString($id);
         $data = $request->validate([
                "name" => ['required', 'string', 'max:100'],
                "price" => ['required', 'string', 'max:25'],
                "color" => ['required', 'string', 'max:250'],
                "stock" => ['required', 'string', 'max:4'],
                "hpp" => ['required', 'string', 'max:25'],
                "margin" => ['required', 'string', 'max:3'],
                "b_layanan" => ['required', 'string', 'max:3'],
                "category_id" => ['required', 'string', 'max:250'],
                "subcategory_id" => ['required', 'string', 'max:250'],
                "description" => ['required', 'string'],
                "pos-front" => ['nullable'],
                "pos-back" => ['nullable'],
                "pos-right" => ['nullable'],
                "pos-left" => ['nullable'],
            ],[],[
                "name" => "Nama produk",
                "price" => "Harga produk",
                "color" => "Warna barang",
                "stock" => "Jumlah barang",
                "margin" => "Margin barang",
                "b_layanan" => "Biaya layanan barang",
                "hpp" => "Harga Pokok Penjualan barang",
                "category_id" => "Kategori barang",
                "subcategory_id" => "Subkategori barang",
                "description" => "Deskripsi barang",
            ]);

           $request->validate([
                'front' => ['nullable', 'mimes:png,jpg,jpeg', 'max:5120'],
                'back' => ['nullable', 'mimes:png,jpg,jpeg', 'max:5120'],
                'left' => ['nullable', 'mimes:png,jpg,jpeg', 'max:5120'],
                'right' => ['nullable', 'mimes:png,jpg,jpeg', 'max:5120'],
                'detail1' => ['nullable', 'mimes:png,jpg,jpeg', 'max:5120'],
                'detail2' => ['nullable', 'mimes:png,jpg,jpeg', 'max:5120'],
                'detail3' => ['nullable', 'mimes:png,jpg,jpeg', 'max:5120'],
                'detail4' => ['nullable', 'mimes:png,jpg,jpeg', 'max:5120'],
            ],[],[
                'front' => "Gambar Depan",
                'back' => "Gambar Belakang",
                'left' => "Gambar Kanan",
                'right' => "Gambar Kiri",
                'detail1' => "Gambar Detail 1",
                'detail2' => "Gambar Detail 2",
                'detail3' => "Gambar Detail 3",
                'detail4' => "Gambar detail 4",
            ]);

            $images = $this->images_repository->getByIdProduct($id);
            $posisi = ['front', 'back', 'right', 'left'];

            try
            {
                foreach ($posisi as $key => $value) {
                    if($data['pos-' . $value] != 'old_true')
                    {
                        if(isset($images[$value][0]))
                        {
                            $image_path = storage_path().'/app/public/'.$images[$value][0]->name;
                            unlink($image_path);
                            $this->images_repository->destroy($images[$value][0]->id);
                        }

                        if($data['pos-' . $value] == $value)
                        {
                            $image_path = $request->file($value)->store('images', 'public');
                            //save image
                            $image_record['id'] = 'IHS-' . Helper::table_id();
                            $image_record["product_id"] = $id;
                            $image_record["position"] = $value;
                            $image_record["name"] = $image_path;
                            $image_record['created_by'] = auth()->user()->id;
                            $image_record['updated_by'] = auth()->user()->id;
                            //proses save
                            $this->images_repository->store($image_record);
                        }
                    }
                    unset($data['pos-' . $value]);
                }
                $this->repository->edit($id, $data);
                return redirect()->route('hampers.index')->with('success', 'Berhasil memperbarui produk ' . $data["name"]);
            }
            catch (Exception $e)
            {
                if (env('APP_DEBUG'))
                {
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
        try 
        {
            $images = $this->images_repository->getByIdProducts($id);
            foreach ($images as $image) 
            {
                $image_path = storage_path().'/app/public/'.$image->name;
                unlink($image_path);
                $this->images_repository->destroy($image->id);
            }
            $this->repository->destroy($id);
            return redirect()->route('hampers.index')->with('success', 'Produk berhasil dihapus');
        }
        catch (\Exception $e)
        {
            if (env('APP_DEBUG')) {
                return $e->getMessage();
            }
            return back()->with('error', 'Terjadi kesalahan saat menghapus produk');
        }
    }
}
