<?php

namespace App\Http\Controllers\Bo\Product;

use App\Repositories\ProductRepository;
use App\Repositories\ImageProductRepository;
use Exception;
use Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\Controller;
use App\Models\Color;
use App\Models\ProductColor;
use App\Models\Products;
use App\Models\Sku;
use Illuminate\Support\Facades\Crypt;

class ProductController extends Controller
{

    private ProductRepository $repository;
    private ImageProductRepository $images_repository;

    protected $data = array();

    public function __construct(ProductRepository $repository, ImageProductRepository $images_repository)
    {
        $this->repository = $repository;
        $this->images_repository = $images_repository;
        $this->data['title'] = 'produk';
        $this->data['view_directory'] = "admin.feature.products.products";
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
                ->editColumn(
                    "price",
                    function ($inquiry) {
                        $r_price = $inquiry["hpp"] / (1 - ($inquiry["margin"] / 100 + $inquiry["b_layanan"] / 100));
                        $res_price = '';
                        if ($r_price > $inquiry["price"]) {
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
                ->editColumn(
                    "color",
                    function ($inquiry) {


                        return $inquiry->colors->pluck('name')->implode(', ');
                    }
                )
                ->addColumn('action', function ($row) {
                    $row["id"] = Crypt::encryptString($row["id"]);
                    $btn = '<form method="POST" action="' . route('product.destroy', $row["id"]) . '">
                                        ' . method_field("DELETE") . '
                                        ' . csrf_field() . '
                                        <a href="' . route("product.edit", $row["id"]) . '" class="btn bg-gradient-info btn-tooltip"><i class="bi bi-pencil-square"></i></a>
                                        <button type="button" id="deleteRow" data-message="' . $row["name"] . '" class="btn bg-gradient-danger btn-tooltip show-alert-delete-box" data-toggle="tooltip" title="Delete"><i class="bi bi-trash"></i></button>
                                    </form>';
                    return $btn;
                })
                ->rawColumns(['action', 'price'])
                ->make(true);
        }
    }

    public function sDatas(Request $request)
    {
        $searchTerm = $request->input('q');
        $filteredProducts = $this->repository->getSearch($searchTerm);
        $formattedData = $filteredProducts->map(function ($product) {
            return [
                'id' => $product->id,
                'text' => $product->name
            ];
        });
        return response()->json(['results' => $formattedData]);
    }

    public function sku_data()
    {
        $sku = Sku::all();
        $formattedData = $sku->map(function ($row) {
            return [
                'id' => $row->id,
                'text' => $row->code
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

        $colorList = Color::orderBy('name')->get();

        // Pastikan ini didefinisikan jika diperlukan
        $data = null;

        if (old('color')) {
            $array_color = array_flip(old('color'));
        } else {
            $array_color = [];
        }

        $ref["url"] = route("product.store");
        return view($this->data['view_directory'] . '.form', compact('ref', 'colorList', 'array_color', 'data'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    //  v1
    // public function store(Request $request)
    // {
    //     // dd($request);

    //     $data = $request->validate([
    //         "name" => ['required', 'string', 'max:100'],
    //         "price" => ['required', 'string', 'max:25'],
    //         "hpp" => ['required', 'string', 'max:25'],
    //         "margin" => ['required', 'string', 'max:3'],
    //         "b_layanan" => ['required', 'string', 'max:3'],
    //         "stock" => ['required', 'string', 'max:4'],
    //         "weight" => ['required', 'string', 'max:6'],
    //         "category_id" => ['required', 'string', 'max:250'],
    //         "color" => ['required', 'max:250'],
    //         "sku_id" => ['required', 'string', 'max:250'],
    //         "subcategory_id" => ['required', 'string', 'max:250'],
    //         "description" => ['required', 'string'],
    //     ], [], [
    //         "name" => "Nama produk",
    //         "price" => "Harga jual produk",
    //         "stock" => "Jumlah barang",
    //         "weight" => "Berat barang",
    //         "margin" => "Margin barang",
    //         "b_layanan" => "Biaya layanan barang",
    //         "hpp" => "Harga Pokok Penjualan barang",
    //         "category_id" => "Kategori barang",
    //         "color" => "Warna barang",
    //         "sku_id" => "Seri barang",
    //         "subcategory_id" => "Subkategori barang",
    //         "description" => "Deskripsi barang",
    //     ]);

    //     $request->validate([
    //         'front' => ['nullable', 'mimes:png,jpg,jpeg', 'max:5120'],
    //         'back' => ['nullable', 'mimes:png,jpg,jpeg', 'max:5120'],
    //         'left' => ['nullable', 'mimes:png,jpg,jpeg', 'max:5120'],
    //         'right' => ['nullable', 'mimes:png,jpg,jpeg', 'max:5120'],
    //         'detail1' => ['nullable', 'mimes:png,jpg,jpeg', 'max:5120'],
    //         'detail2' => ['nullable', 'mimes:png,jpg,jpeg', 'max:5120'],
    //         'detail3' => ['nullable', 'mimes:png,jpg,jpeg', 'max:5120'],
    //         'detail4' => ['nullable', 'mimes:png,jpg,jpeg', 'max:5120'],
    //     ], [], [
    //         'front' => "Gambar Depan",
    //         'back' => "Gambar Belakang",
    //         'left' => "Gambar Kanan",
    //         'right' => "Gambar Kiri",
    //         'detail1' => "Gambar Detail 1",
    //         'detail2' => "Gambar Detail 2",
    //         'detail3' => "Gambar Detail 3",
    //         'detail4' => "Gambar detail 4",
    //     ]);

    //     try {
    //         $data['id'] = 'PDT-' . Helper::table_id();
    //         $data['created_by'] = auth()->user()->id;
    //         $data['updated_by'] = auth()->user()->id;
    //         $data['slug'] = $this->repository->sluggable($data['name']);
    //         // $this->repository->store($data)

    //         $produk = new Products();

    //         $produk->id = 'PDT-' . Helper::table_id();
    //         $produk->name = $request->name;
    //         $produk->slug = $this->repository->sluggable($data['name']);
    //         $produk->description = $request->description;
    //         $produk->category_id = $request->category_id;
    //         $produk->subcategory_id = $request->subcategory_id;
    //         $produk->weight = $request->weight;
    //         $produk->price = $request->price;
    //         $produk->color = '';
    //         $produk->stock = $request->stock;
    //         $produk->hpp = $request->hpp;
    //         $produk->margin = $request->margin;
    //         $produk->b_layanan = $request->b_layanan;
    //         $produk->created_by = auth()->user()->id;
    //         $produk->updated_by = auth()->user()->id;
    //         $produk->sku_id = $request->sku_id;

    //         $produk->save();

    //         $produk->colors()->attach($request->color);

    //         foreach ($request->file() as $key => $img) {
    //             $image_path = $request->file($key)->store('images', 'public');
    //             //save image
    //             $image_record['id'] = 'IPT-' . Helper::table_id();
    //             $image_record["product_id"] = $data['id'];
    //             $image_record["position"] = $key;
    //             $image_record["name"] = $image_path;
    //             $image_record['created_by'] = auth()->user()->id;
    //             $image_record['updated_by'] = auth()->user()->id;
    //             //proses save
    //             $this->images_repository->store($image_record);
    //         }

    //         return redirect()->route('product.index')->with('success', 'Berhasil menambah produk ' . $data["name"]);
    //     } catch (Exception $e) {
    //         if (env('APP_DEBUG')) {
    //             return $e->getMessage();
    //         }
    //         return back()->with('error', "Oops..!! Terjadi keesalahan saat menyimpan data")->withInput($request->input);
    //     }
    // }

    // v2
    // public function store(Request $request)
    // {
    //     $data = $request->validate([
    //         "name" => ['required', 'string', 'max:100'],
    //         "price" => ['required', 'string', 'max:25'],
    //         "hpp" => ['required', 'string', 'max:25'],
    //         "margin" => ['required', 'string', 'max:3'],
    //         "b_layanan" => ['required', 'string', 'max:3'],
    //         "stock" => ['required', 'string', 'max:4'],
    //         "weight" => ['required', 'string', 'max:6'],
    //         "category_id" => ['required', 'string', 'max:250'],
    //         "color" => ['required', 'max:250'],
    //         "sku_id" => ['required', 'string', 'max:250'],
    //         "subcategory_id" => ['required', 'string', 'max:250'],
    //         "description" => ['required', 'string'],
    //     ], [], [
    //         "name" => "Nama produk",
    //         "price" => "Harga jual produk",
    //         "stock" => "Jumlah barang",
    //         "weight" => "Berat barang",
    //         "margin" => "Margin barang",
    //         "b_layanan" => "Biaya layanan barang",
    //         "hpp" => "Harga Pokok Penjualan barang",
    //         "category_id" => "Kategori barang",
    //         "color" => "Warna barang",
    //         "sku_id" => "Seri barang",
    //         "subcategory_id" => "Subkategori barang",
    //         "description" => "Deskripsi barang",
    //     ]);

    //     $request->validate([
    //         'front' => ['nullable', 'mimes:png,jpg,jpeg', 'max:5120'],
    //         'back' => ['nullable', 'mimes:png,jpg,jpeg', 'max:5120'],
    //         'left' => ['nullable', 'mimes:png,jpg,jpeg', 'max:5120'],
    //         'right' => ['nullable', 'mimes:png,jpg,jpeg', 'max:5120'],
    //         'detail1' => ['nullable', 'mimes:png,jpg,jpeg', 'max:5120'],
    //         'detail2' => ['nullable', 'mimes:png,jpg,jpeg', 'max:5120'],
    //         'detail3' => ['nullable', 'mimes:png,jpg,jpeg', 'max:5120'],
    //         'detail4' => ['nullable', 'mimes:png,jpg,jpeg', 'max:5120'],
    //     ], [], [
    //         'front' => "Gambar Depan",
    //         'back' => "Gambar Belakang",
    //         'left' => "Gambar Kanan",
    //         'right' => "Gambar Kiri",
    //         'detail1' => "Gambar Detail 1",
    //         'detail2' => "Gambar Detail 2",
    //         'detail3' => "Gambar Detail 3",
    //         'detail4' => "Gambar Detail 4",
    //     ]);

    //     try {
    //         $data['id'] = 'PDT-' . Helper::table_id();
    //         $data['created_by'] = auth()->user()->id;
    //         $data['updated_by'] = auth()->user()->id;
    //         $data['slug'] = $this->repository->sluggable($data['name']);

    //         $produk = new Products();
    //         $produk->id = $data['id'];
    //         $produk->name = $data['name'];
    //         $produk->slug = $data['slug'];
    //         $produk->description = $data['description'];
    //         $produk->category_id = $data['category_id'];
    //         $produk->subcategory_id = $data['subcategory_id'];
    //         $produk->weight = $data['weight'];
    //         $produk->price = $data['price'];
    //         $produk->color = '';
    //         $produk->stock = $data['stock'];
    //         $produk->hpp = $data['hpp'];
    //         $produk->margin = $data['margin'];
    //         $produk->b_layanan = $data['b_layanan'];
    //         $produk->created_by = auth()->user()->id;
    //         $produk->updated_by = auth()->user()->id;
    //         $produk->sku_id = $data['sku_id'];
    //         $produk->save();

    //         // Menyimpan warna produk
    //         $produk->colors()->attach($data['color']);

    //         // Proses penyimpanan gambar
    //         if ($request->hasFile()) {
    //             foreach ($request->file() as $key => $img) {
    //                 // Memindahkan file ke direktori penyimpanan
    //                 $image_path = $img->store('images', 'public');

    //                 // Mempersiapkan data gambar
    //                 $image_record['id'] = 'IPT-' . Helper::table_id();
    //                 $image_record["product_id"] = $produk->id;
    //                 $image_record["position"] = $key;
    //                 $image_record["name"] = $image_path;
    //                 $image_record['created_by'] = auth()->user()->id;
    //                 $image_record['updated_by'] = auth()->user()->id;

    //                 // Simpan data gambar ke repository
    //                 $this->images_repository->store($image_record);
    //             }
    //         }

    //         return redirect()->route('product.index')->with('success', 'Berhasil menambah produk ' . $data["name"]);
    //     } catch (Exception $e) {
    //         if (env('APP_DEBUG')) {
    //             return $e->getMessage();
    //         }
    //         return back()->with('error', "Oops..!! Terjadi kesalahan saat menyimpan data")->withInput($request->input);
    //     }
    // }

    // v3 {final}
    public function store(Request $request)
    {
        $data = $request->validate([
            "name" => ['required', 'string', 'max:100'],
            "price" => ['required', 'string', 'max:25'],
            "hpp" => ['required', 'string', 'max:25'],
            "margin" => ['required', 'string', 'max:3'],
            "b_layanan" => ['required', 'string', 'max:3'],
            "stock" => ['required', 'string', 'max:4'],
            "weight" => ['required', 'string', 'max:6'],
            "category_id" => ['required', 'string', 'max:250'],
            "color" => ['required', 'max:250'],
            "sku_id" => ['required', 'string', 'max:250'],
            "subcategory_id" => ['required', 'string', 'max:250'],
            "description" => ['required', 'string'],
        ], [], [
            "name" => "Nama produk",
            "price" => "Harga jual produk",
            "stock" => "Jumlah barang",
            "weight" => "Berat barang",
            "margin" => "Margin barang",
            "b_layanan" => "Biaya layanan barang",
            "hpp" => "Harga Pokok Penjualan barang",
            "category_id" => "Kategori barang",
            "color" => "Warna barang",
            "sku_id" => "Seri barang",
            "subcategory_id" => "Subkategori barang",
            "description" => "Deskripsi barang",
        ]);

        $request->validate([
            'front' => ['nullable', 'mimes:png,jpg,jpeg,JPG', 'max:5120'],
            'back' => ['nullable', 'mimes:png,jpg,jpeg,JPG', 'max:5120'],
            'left' => ['nullable', 'mimes:png,jpg,jpeg,JPG', 'max:5120'],
            'right' => ['nullable', 'mimes:png,jpg,jpeg,JPG', 'max:5120'],
            'detail1' => ['nullable', 'mimes:png,jpg,jpeg,JPG', 'max:5120'],
            'detail2' => ['nullable', 'mimes:png,jpg,jpeg,JPG', 'max:5120'],
            'detail3' => ['nullable', 'mimes:png,jpg,jpeg,JPG', 'max:5120'],
            'detail4' => ['nullable', 'mimes:png,jpg,jpeg,JPG', 'max:5120'],
        ], [], [
            'front' => "Gambar Depan",
            'back' => "Gambar Belakang",
            'left' => "Gambar Kanan",
            'right' => "Gambar Kiri",
            'detail1' => "Gambar Detail 1",
            'detail2' => "Gambar Detail 2",
            'detail3' => "Gambar Detail 3",
            'detail4' => "Gambar Detail 4",
        ]);

        try {
            $produk = new Products();
            $produk->id = 'PDT-' . Helper::table_id();
            $produk->name = $request->name;
            $produk->slug = $this->repository->sluggable($request->name);
            $produk->description = $request->description;
            $produk->category_id = $request->category_id;
            $produk->subcategory_id = $request->subcategory_id;
            $produk->weight = $request->weight;
            $produk->price = $request->price;
            $produk->color = '';
            $produk->stock = $request->stock;
            $produk->hpp = $request->hpp;
            $produk->margin = $request->margin;
            $produk->b_layanan = $request->b_layanan;
            $produk->created_by = auth()->user()->id;
            $produk->updated_by = auth()->user()->id;
            $produk->sku_id = $request->sku_id;
            $produk->save(); // Pastikan produk disimpan terlebih dahulu

            // Attach colors
            $produk->colors()->attach($request->color);

            // Menyimpan gambar
            foreach ($request->file() as $key => $img) {
                $image_path = $img->store('images', 'public');

                // Menyimpan data gambar ke database
                $image_record = [
                    'id' => 'IPT-' . Helper::table_id(),
                    "product_id" => $produk->id, // Menggunakan ID dari produk yang baru saja disimpan
                    "position" => $key,
                    "name" => $image_path,
                    'created_by' => auth()->user()->id,
                    'updated_by' => auth()->user()->id
                ];
                $this->images_repository->store($image_record); // Simpan record gambar ke database
            }

            return redirect()->route('product.index')->with('success', 'Berhasil menambah produk ' . $produk->name);
        } catch (Exception $e) {
            if (env('APP_DEBUG')) {
                return $e->getMessage();
            }
            return back()->with('error', "Oops..!! Terjadi kesalahan saat menyimpan data")->withInput($request->input);
        }
    }



    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Products  $products
     * @return \Illuminate\Http\Response
     */
    public function show($products)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Products  $products
     * @return \Illuminate\Http\Response
     */
    public function edit(string $id)
    {
        $id = Crypt::decryptString($id);
        $ref = $this->data;
        $data = $this->repository->getById($id);
        $data_gambar = $this->images_repository->getByIdProduct($id);
        $id = Crypt::encryptString($id);
        $ref["url"] = route("product.update", $id);

        $colorList = Color::orderBy('name')->get();



        if (old('color')) {

            if (old('color')) {
                $array_color = array_flip(old('color'));
            } else {
                $array_color = [];
            }
        } else {

            if ($data['colors']) {
                $array_color = $data['colors']->pluck('name', 'id')->toArray();
            } else {
                $array_color = [];
            }
        }




        return view($this->data['view_directory'] . '.form', compact('ref', "data", 'data_gambar', 'colorList', 'array_color'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Products  $products
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, string $id)
    {
        // dd($request);

        $id = Crypt::decryptString($id);
        $data = $request->validate([
            "name" => ['required', 'string', 'max:100'],
            "price" => ['required', 'string', 'max:25'],
            "stock" => ['required', 'string', 'max:4'],
            "weight" => ['required', 'string', 'max:6'],
            "color" => ['required', 'max:250'],
            "sku_id" => ['required', 'string', 'max:250'],
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
        ], [], [
            "name" => "Nama produk",
            "price" => "Harga produk",
            "stock" => "Jumlah barang",
            "color" => "Warna barang",
            "sku_id" => "Seri barang",
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
        ], [], [
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

        try {
            foreach ($posisi as $key => $value) {
                if ($data['pos-' . $value] != 'old_true') {
                    if (isset($images[$value][0])) {
                        $image_path = storage_path() . '/app/public/' . $images[$value][0]->name;
                        unlink($image_path);
                        $this->images_repository->destroy($images[$value][0]->id);
                    }

                    if ($data['pos-' . $value] == $value) {
                        $image_path = $request->file($value)->store('images', 'public');
                        //save image
                        $image_record['id'] = 'IPT-' . Helper::table_id();
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
            // dd($data);
            $this->repository->edit($id, $data);

            $produk = Products::find($id);

            $produk->name = $request->name;
            $produk->slug = $this->repository->sluggable($data['name']);
            $produk->description = $request->description;
            $produk->category_id = $request->category_id;
            $produk->subcategory_id = $request->subcategory_id;
            $produk->weight = $request->weight;
            $produk->price = $request->price;
            $produk->color = '';
            $produk->stock = $request->stock;
            $produk->hpp = $request->hpp;
            $produk->margin = $request->margin;
            $produk->b_layanan = $request->b_layanan;
            $produk->created_by = auth()->user()->id;
            $produk->updated_by = auth()->user()->id;
            $produk->sku_id = $request->sku_id;

            $produk->save();

            $produk->colors()->sync($request->color);

            return redirect()->route('product.index')->with('success', 'Berhasil memperbarui produk ' . $data["name"]);
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
     * @param  \App\Models\Products  $products
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $id)
    {
        $id = Crypt::decryptString($id);
        try {
            $images = $this->images_repository->getByIdProducts($id);
            foreach ($images as $image) {
                $image_path = storage_path() . '/app/public/' . $image->name;
                unlink($image_path);
                $this->images_repository->destroy($image->id);
            }
            $this->repository->destroy($id);
            return redirect()->route('product.index')->with('success', 'Produk berhasil dihapus');
        } catch (\Exception $e) {
            if (env('APP_DEBUG')) {
                return $e->getMessage();
            }
            return back()->with('error', 'Terjadi kesalahan saat menghapus produk');
        }
    }
}
