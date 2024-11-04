<?php

namespace App\Http\Controllers\Bo\Product;

use App\Http\Controllers\Controller;
use App\Models\Color;
use App\Models\ProductColor;
use App\Models\ProductImages;
use App\Models\Products;
use App\Models\Sku;
use App\Repositories\ImageProductRepository;
use App\Repositories\ProductRepository;
use Exception;
use Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\URL;
use Yajra\DataTables\Facades\DataTables;

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

    // public function datas(Request $request)
    // {
    //     if ($request->ajax()) {
    //         try {
    //             $data = $this->repository->getAll();
    //         } catch (Exception $e) {
    //             dd($e);
    //         }
    //         return DataTables::of($data)
    //             ->addIndexColumn()
    //             ->editColumn(
    //                 "price",
    //                 function ($inquiry) {
    //                     $r_price = $inquiry["hpp"] / (1 - ($inquiry["margin"] / 100 + $inquiry["b_layanan"] / 100));
    //                     $res_price = '';
    //                     if ($r_price > $inquiry["price"]) {
    //                         $res_price = ' <span class="badge bg-danger">Update Harga</span>';
    //                     }
    //                     return Helper::to_rupiah($inquiry["price"]) . $res_price;
    //                 }
    //             )
    //             ->editColumn(
    //                 "stock",
    //                 function ($inquiry) {
    //                     return $inquiry["stock"] . " Pcs";
    //                 }
    //             )
    //             ->editColumn(
    //                 "color",
    //                 function ($inquiry) {


    //                     return $inquiry->colors->pluck('name')->implode(', ');
    //                 }
    //             )
    //             ->addColumn('action', function ($row) {
    //                 $row["id"] = Crypt::encryptString($row["id"]);
    //                 $btn = '<form method="POST" action="' . route('product.destroy', $row["id"]) . '">
    //                                     ' . method_field("DELETE") . '
    //                                     ' . csrf_field() . '
    //                                     <a href="' . route("product.edit", $row["id"]) . '" class="btn bg-gradient-info btn-tooltip"><i class="bi bi-pencil-square"></i></a>
    //                                     <button type="button" id="deleteRow" data-message="' . $row["name"] . '" class="btn bg-gradient-danger btn-tooltip show-alert-delete-box" data-toggle="tooltip" title="Delete"><i class="bi bi-trash"></i></button>
    //                                 </form>';
    //                 return $btn;
    //             })
    //             ->rawColumns(['action', 'price'])
    //             ->make(true);
    //     }
    // }

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
                        $productColors = $this->repository->getProductColorSum($inquiry->id);

                        return $productColors . " Pcs";
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
        $ref['sku'] = \App\Models\Sku::all();

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

    // v3 {final}
    // public function store(Request $request)
    // {
    //     dd($request->all());
    //     $data = $request->validate([
    //         "name" => ['required', 'string', 'max:100'],
    //         "price" => ['required', 'string', 'max:25'],
    //         "hpp" => ['required', 'string', 'max:25'],
    //         "margin" => ['required', 'string', 'max:3'],
    //         "b_layanan" => ['required', 'string', 'max:3'],
    //         // "stock" => ['required', 'string', 'max:4'],
    //         "weight" => ['required', 'string', 'max:6'],
    //         "category_id" => ['required', 'string', 'max:250'],
    //         "color" => ['required', 'max:250'],
    //         "sku_id" => ['required', 'string', 'max:250'],
    //         "subcategory_id" => ['required', 'string', 'max:250'],
    //         "description" => ['required', 'string'],
    //     ], [], [
    //         "name" => "Nama produk",
    //         "price" => "Harga jual produk",
    //         // "stock" => "Jumlah barang",
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
    //         'front' => ['nullable', 'mimes:png,jpg,jpeg,JPG', 'max:3072'],
    //         'back' => ['nullable', 'mimes:png,jpg,jpeg,JPG', 'max:3072'],
    //         'left' => ['nullable', 'mimes:png,jpg,jpeg,JPG', 'max:3072'],
    //         'right' => ['nullable', 'mimes:png,jpg,jpeg,JPG', 'max:3072'],
    //         'detail1' => ['nullable', 'mimes:png,jpg,jpeg,JPG', 'max:3072'],
    //         'detail2' => ['nullable', 'mimes:png,jpg,jpeg,JPG', 'max:3072'],
    //         'detail3' => ['nullable', 'mimes:png,jpg,jpeg,JPG', 'max:3072'],
    //         'detail4' => ['nullable', 'mimes:png,jpg,jpeg,JPG', 'max:3072'],
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
    //         // dd($request->all());
    //         $produk = new Products();
    //         $produk->id = 'PDT-' . Helper::table_id();
    //         $produk->name = $request->name;
    //         $produk->slug = $this->repository->sluggable($request->name);
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
    //         $produk->save(); // Pastikan produk disimpan terlebih dahulu

    //         // Attach colors
    //         $produk->colors()->attach($request->color);

    //         // Menyimpan gambar
    //         foreach ($request->file() as $key => $img) {
    //             $image_path = $img->store('images', 'public');

    //             // Menyimpan data gambar ke database
    //             $image_record = [
    //                 'id' => 'IPT-' . Helper::table_id(),
    //                 "product_id" => $produk->id, // Menggunakan ID dari produk yang baru saja disimpan
    //                 "position" => $key,
    //                 "name" => $image_path,
    //                 'created_by' => auth()->user()->id,
    //                 'updated_by' => auth()->user()->id
    //             ];
    //             $this->images_repository->store($image_record); // Simpan record gambar ke database
    //         }

    //         return redirect()->route('product.index')->with('success', 'Berhasil menambah produk ' . $produk->name);
    //     } catch (Exception $e) {
    //         if (env('APP_DEBUG')) {
    //             return $e->getMessage();
    //         }
    //         return back()->with('error', "Oops..!! Terjadi kesalahan saat menyimpan data")->withInput($request->input);
    //     }
    // }

    // public function store(Request $request)
    // {
    //     // Ambil daftar warna berdasarkan id
    //     $allData = $request->all();

    //     // digunakan untuk menfilter data color yang lebih dari 0
    //     $countData = array_filter($allData, function ($value, $key) {
    //         return str_ends_with($key, '-count') && (int)$value > 0;
    //     }, ARRAY_FILTER_USE_BOTH);


    //     $colorList = Color::select('id')->orderBy('name')->get();

    //     $colorCountRules = [];
    //     foreach ($colorList as $color) {
    //         $colorCountRules["COL-{$color->id}-count"] = ['integer', 'min:0'];
    //     }

    //     // Validasi input utama
    //     $data = $request->validate(array_merge([
    //         "name" => ['required', 'string', 'max:100'],
    //         "price" => ['required', 'string', 'max:25'],
    //         "hpp" => ['required', 'string', 'max:25'],
    //         "margin" => ['required', 'string', 'max:3'],
    //         "b_layanan" => ['required', 'string', 'max:3'],
    //         "weight" => ['required', 'string', 'max:6'],
    //         "category_id" => ['required', 'string', 'max:250'],
    //         "color" => ['required', 'max:250'],
    //         "sku_id" => ['required', 'string', 'max:250'],
    //         "subcategory_id" => ['required', 'string', 'max:250'],
    //         "description" => ['required', 'string'],
    //     ], $colorCountRules), [], [
    //         "name" => "Nama produk",
    //         "price" => "Harga jual produk",
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
    //         'front' => ['nullable', 'mimes:png,jpg,jpeg,JPG', 'max:3072'],
    //         'back' => ['nullable', 'mimes:png,jpg,jpeg,JPG', 'max:3072'],
    //         'left' => ['nullable', 'mimes:png,jpg,jpeg,JPG', 'max:3072'],
    //         'right' => ['nullable', 'mimes:png,jpg,jpeg,JPG', 'max:3072'],
    //         'detail1' => ['nullable', 'mimes:png,jpg,jpeg,JPG', 'max:3072'],
    //         'detail2' => ['nullable', 'mimes:png,jpg,jpeg,JPG', 'max:3072'],
    //         'detail3' => ['nullable', 'mimes:png,jpg,jpeg,JPG', 'max:3072'],
    //         'detail4' => ['nullable', 'mimes:png,jpg,jpeg,JPG', 'max:3072'],
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

    //     dd($data);

    //     try {
    //         // Simpan produk
    //         $produk = new Products();
    //         $produk->id = 'PDT-' . Helper::table_id();
    //         $produk->name = $request->name;
    //         $produk->slug = $this->repository->sluggable($request->name);
    //         $produk->description = $request->description;
    //         $produk->category_id = $request->category_id;
    //         $produk->subcategory_id = $request->subcategory_id;
    //         $produk->weight = $request->weight;
    //         $produk->price = $request->price;
    //         $produk->color = '';
    //         $produk->stock = 0;
    //         $produk->hpp = $request->hpp;
    //         $produk->margin = $request->margin;
    //         $produk->b_layanan = $request->b_layanan;
    //         $produk->created_by = auth()->user()->id;
    //         $produk->updated_by = auth()->user()->id;
    //         $produk->sku_id = $request->sku_id;
    //         $produk->save();

    //         // Attach colors
    //         $produk->colors()->attach($request->color);

    //         // Menyimpan gambar
    //         foreach ($request->file() as $key => $img) {
    //             $image_path = $img->store('images', 'public');

    //             $image_record = [
    //                 'id' => 'IPT-' . Helper::table_id(),
    //                 "product_id" => $produk->id,
    //                 "position" => $key,
    //                 "name" => $image_path,
    //                 'created_by' => auth()->user()->id,
    //                 'updated_by' => auth()->user()->id
    //             ];
    //             $this->images_repository->store($image_record);
    //         }

    //         return redirect()->route('product.index')->with('success', 'Berhasil menambah produk ' . $produk->name);
    //     } catch (Exception $e) {
    //         if (env('APP_DEBUG')) {
    //             return $e->getMessage();
    //         }
    //         return back()->with('error', "Oops..!! Terjadi kesalahan saat menyimpan data")->withInput($request->input);
    //     }
    // }

    public function store(Request $request)
    {
        // Ambil daftar warna berdasarkan id
        $allData = $request->all();

        // Digunakan untuk menfilter data color yang lebih dari 0
        $countData = array_filter($allData, function ($value, $key) {
            return str_ends_with($key, '-count') && (int)$value > 0;
        }, ARRAY_FILTER_USE_BOTH);

        $colorList = Color::select('id')->orderBy('name')->get();

        // Buat aturan validasi untuk count berdasarkan daftar warna
        $colorCountRules = [];
        foreach ($colorList as $color) {
            $colorCountRules["COL-{$color->id}-count"] = ['integer', 'min:0'];
        }

        // Validasi input utama
        $data = $request->validate(array_merge([
            "name" => ['required', 'string', 'max:100'],
            "price" => ['required', 'string', 'max:25'],
            "hpp" => ['required', 'string', 'max:25'],
            "margin" => ['required', 'string', 'max:3'],
            "b_layanan" => ['required', 'string', 'max:3'],
            "weight" => ['required', 'string', 'max:6'],
            "category_id" => ['required', 'string', 'max:250'],
            "color" => ['required', 'max:250'],
            "sku_id" => ['required', 'string', 'max:250'],
            "subcategory_id" => ['required', 'string', 'max:250'],
            "description" => ['required', 'string'],
        ], $colorCountRules), [], [
            "name" => "Nama produk",
            "price" => "Harga jual produk",
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

        // Validasi gambar
        $request->validate([
            'front' => ['nullable', 'mimes:png,jpg,jpeg,JPG', 'max:3072'],
            'back' => ['nullable', 'mimes:png,jpg,jpeg,JPG', 'max:3072'],
            'left' => ['nullable', 'mimes:png,jpg,jpeg,JPG', 'max:3072'],
            'right' => ['nullable', 'mimes:png,jpg,jpeg,JPG', 'max:3072'],
            'detail1' => ['nullable', 'mimes:png,jpg,jpeg,JPG', 'max:3072'],
            'detail2' => ['nullable', 'mimes:png,jpg,jpeg,JPG', 'max:3072'],
            'detail3' => ['nullable', 'mimes:png,jpg,jpeg,JPG', 'max:3072'],
            'detail4' => ['nullable', 'mimes:png,jpg,jpeg,JPG', 'max:3072'],
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
            // Simpan produk
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
            $produk->stock = 0;
            $produk->hpp = $request->hpp;
            $produk->margin = $request->margin;
            $produk->b_layanan = $request->b_layanan;
            $produk->created_by = auth()->user()->id;
            $produk->updated_by = auth()->user()->id;
            $produk->sku_id = $request->sku_id;
            $produk->save();

            // Attach colors with count
            foreach ($countData as $key => $value) {
                // Ekstrak ID warna dari key, misalnya dari "COL-20240603103554401767-count"
                $colorId = str_replace(['COL-', '-count'], '', $key);
                $produk->colors()->attach($colorId, ['count' => (int)$value]);
            }

            // Menyimpan gambar
            foreach ($request->file() as $key => $img) {
                $image_path = $img->store('images', 'public');

                $image_record = [
                    'id' => 'IPT-' . Helper::table_id(),
                    "product_id" => $produk->id,
                    "position" => $key,
                    "name" => $image_path,
                    'created_by' => auth()->user()->id,
                    'updated_by' => auth()->user()->id
                ];
                $this->images_repository->store($image_record);
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
    // public function edit(string $id)
    // {
    //     $id = Crypt::decryptString($id);
    //     $ref = $this->data;
    //     $ref['sku'] = \App\Models\Sku::all();

    //     $data = $this->repository->getById($id);
    //     $data_gambar = $this->images_repository->getByIdProduct($id);
    //     $id = Crypt::encryptString($id);
    //     $ref["url"] = route("product.update", $id);

    //     $colorList = Color::orderBy('name')->get();



    //     if (old('color')) {

    //         if (old('color')) {
    //             $array_color = array_flip(old('color'));
    //         } else {
    //             $array_color = [];
    //         }
    //     } else {

    //         if ($data['colors']) {
    //             $array_color = $data['colors']->pluck('name', 'id')->toArray();
    //         } else {
    //             $array_color = [];
    //         }
    //     }




    //     return view($this->data['view_directory'] . '.form', compact('ref', "data", 'data_gambar', 'colorList', 'array_color'));
    // }
    // public function edit(string $id)
    // {
    //     $id = Crypt::decryptString($id);
    //     $ref = $this->data;
    //     $ref['sku'] = \App\Models\Sku::all();

    //     $data = $this->repository->getById($id);
    //     $data_gambar = $this->images_repository->getByIdProduct($id);
    //     $id = Crypt::encryptString($id);
    //     $ref["url"] = route("product.update", $id);

    //     $colorList = Color::orderBy('name')->get();

    //     if (old('color')) {
    //         $array_color = array_flip(old('color'));
    //     } else {
    //         if ($data['colors']) {
    //             // Menggabungkan warna dengan jumlah warna terkait
    //             $array_color = $data['colors']->pluck('pivot.count', 'id')->toArray();
    //         } else {
    //             $array_color = [];
    //         }
    //     }

    //     $product_color = $this->repository->getProductColor($data->id);

    //     return view($this->data['view_directory'] . '.form', compact('ref', "data", 'data_gambar', 'colorList', 'array_color', 'product_color'));
    // }
    public function edit(string $id)
    {
        $id = Crypt::decryptString($id);
        $ref = $this->data;
        $ref['sku'] = \App\Models\Sku::all();

        $data = $this->repository->getById($id);
        $data_gambar = $this->images_repository->getByIdProduct($id);
        $id = Crypt::encryptString($id);
        $ref["url"] = route("product.update", $id);

        $colorList = Color::orderBy('name')->get();

        // Mengambil data warna dengan jumlah dari produk terkait
        $productColors = $this->repository->getProductColor($data->id);
        $array_color = $productColors->pluck('count', 'color_id')->toArray();

        $modifiedArrayColor = [];
        foreach ($array_color as $key => $value) {
            $modifiedArrayColor['COL-' . $key] = $value;
        }

        // dd($modifiedArrayColor['COL-20240603103848671427']);

        return view($this->data['view_directory'] . '.form', compact('ref', "data", 'data_gambar', 'colorList', 'array_color', 'modifiedArrayColor'));
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Products  $products
     * @return \Illuminate\Http\Response
     */

    // public function update(Request $request, string $id)
    // {
    //     dd($request->all());
    //     // Dump data untuk debugging (bisa dihapus jika tidak diperlukan)
    //     // Dekripsi ID produk
    //     $id = Crypt::decryptString($id);

    //     // Validasi data input produk
    //     $data = $request->validate([
    //         "name" => ['required', 'string', 'max:100'],
    //         "price" => ['required', 'string', 'max:25'],
    //         "stock" => ['required', 'string', 'max:4'],
    //         "weight" => ['required', 'string', 'max:6'],
    //         "color" => ['required', 'max:250'],
    //         "sku_id" => ['required', 'string', 'max:250'],
    //         "hpp" => ['required', 'string', 'max:25'],
    //         "margin" => ['required', 'string', 'max:3'],
    //         "b_layanan" => ['required', 'string', 'max:3'],
    //         "category_id" => ['required', 'string', 'max:250'],
    //         "subcategory_id" => ['required', 'string', 'max:250'],
    //         "description" => ['required', 'string'],
    //     ], [], [
    //         "name" => "Nama produk",
    //         "price" => "Harga produk",
    //         "stock" => "Jumlah barang",
    //         "color" => "Warna barang",
    //         "sku_id" => "Seri barang",
    //         "weight" => "Berat barang",
    //         "margin" => "Margin barang",
    //         "b_layanan" => "Biaya layanan barang",
    //         "hpp" => "Harga Pokok Penjualan barang",
    //         "category_id" => "Kategori barang",
    //         "subcategory_id" => "Subkategori barang",
    //         "description" => "Deskripsi barang",
    //     ]);

    //     // Validasi gambar (jika ada gambar baru)
    //     $request->validate([
    //         'front' => ['nullable', 'mimes:png,jpg,jpeg', 'max:5120'],
    //         'back' => ['nullable', 'mimes:png,jpg,jpeg', 'max:5120'],
    //         'left' => ['nullable', 'mimes:png,jpg,jpeg', 'max:5120'],
    //         'right' => ['nullable', 'mimes:png,jpg,jpeg', 'max:5120'],
    //         'detail1' => ['nullable', 'mimes:png,jpg,jpeg', 'max:5120'],
    //         'detail2' => ['nullable', 'mimes:png,jpg,jpeg', 'max:5120'],
    //         'detail3' => ['nullable', 'mimes:png,jpg,jpeg', 'max:5120'],
    //         'detail4' => ['nullable', 'mimes:png,jpg,jpeg', 'max:5120'],
    //     ]);

    //     try {
    //         // Ambil produk berdasarkan ID
    //         $produk = Products::findOrFail($id);

    //         // Update data produk
    //         $produk->name = $request->name;
    //         $produk->slug = $this->repository->sluggable($request->name);
    //         $produk->description = $request->description;
    //         $produk->category_id = $request->category_id;
    //         $produk->subcategory_id = $request->subcategory_id;
    //         $produk->weight = $request->weight;
    //         $produk->price = $request->price;
    //         $produk->color = $request->color;
    //         $produk->stock = $request->stock;
    //         $produk->hpp = $request->hpp;
    //         $produk->margin = $request->margin;
    //         $produk->b_layanan = $request->b_layanan;
    //         $produk->sku_id = $request->sku_id;
    //         $produk->updated_by = auth()->user()->id;
    //         $produk->save();

    //         // Posisi gambar yang harus dicek
    //         $positions = [
    //             'front' => 'pos-front',
    //             'back' => 'pos-back',
    //             'left' => 'pos-left',
    //             'right' => 'pos-right',
    //             'detail1' => 'pos-detail1',
    //             'detail2' => 'pos-detail2',
    //             'detail3' => 'pos-detail3',
    //             'detail4' => 'pos-detail4',
    //         ];

    //         foreach ($positions as $position => $pos_request_key) {
    //             // Ambil gambar lama dari database
    //             $existingImage = $this->images_repository->getByPosition($id, $position);

    //             // Hapus gambar jika pos_request_key === null atau ada file baru yang diupload
    //             if ($request->$pos_request_key === null || $request->hasFile($position)) {
    //                 if ($existingImage) {
    //                     // Hapus gambar dari folder
    //                     $oldImagePath = storage_path('app/public/' . $existingImage->name);
    //                     if (file_exists($oldImagePath)) {
    //                         unlink($oldImagePath);
    //                     }

    //                     // Hapus data dari database
    //                     $this->images_repository->destroy($existingImage->id);
    //                 }

    //                 // Jika ada file baru yang diupload, simpan gambar baru
    //                 if ($request->hasFile($position)) {
    //                     $imagePath = $request->file($position)->store('images', 'public');
    //                     $imageRecord = [
    //                         'id' => 'IPT-' . Helper::table_id(),
    //                         'product_id' => $id,
    //                         'position' => $position,
    //                         'name' => $imagePath,
    //                         'created_by' => auth()->user()->id,
    //                         'updated_by' => auth()->user()->id,
    //                     ];
    //                     // Simpan data gambar baru ke database
    //                     $this->images_repository->store($imageRecord);
    //                 }
    //             }
    //         }

    //         return redirect()->route('product.index')->with('success', 'Produk berhasil diperbarui.');
    //     } catch (Exception $e) {
    //         if (env('APP_DEBUG')) {
    //             return $e->getMessage();
    //         }
    //         return back()->with('error', 'Terjadi kesalahan saat memperbarui produk.')->withInput();
    //     }
    // }

    // public function update(Request $request, string $id)
    // {
    //     // Dekripsi ID produk
    //     $id = Crypt::decryptString($id);

    //     // Ambil data input untuk warna dengan count lebih dari 0
    //     $allData = $request->all();
    //     $countData = array_filter($allData, function ($value, $key) {
    //         return str_ends_with($key, '-count') && (int)$value > 0;
    //     }, ARRAY_FILTER_USE_BOTH);

    //     $colorList = Color::select('id')->orderBy('name')->get();

    //     // Validasi data produk
    //     $data = $request->validate(array_merge([
    //         "name" => ['required', 'string', 'max:100'],
    //         "price" => ['required', 'string', 'max:25'],
    //         "stock" => ['required', 'string', 'max:4'],
    //         "weight" => ['required', 'string', 'max:6'],
    //         "color" => ['required', 'max:250'],
    //         "sku_id" => ['required', 'string', 'max:250'],
    //         "hpp" => ['required', 'string', 'max:25'],
    //         "margin" => ['required', 'string', 'max:3'],
    //         "b_layanan" => ['required', 'string', 'max:3'],
    //         "category_id" => ['required', 'string', 'max:250'],
    //         "subcategory_id" => ['required', 'string', 'max:250'],
    //         "description" => ['required', 'string'],
    //     ], array_combine(
    //         array_map(fn($color) => "COL-{$color->id}-count", $colorList->pluck('id')->toArray()),
    //         array_fill(0, count($colorList), ['integer', 'min:0'])
    //     )), [], [
    //         "name" => "Nama produk",
    //         "price" => "Harga produk",
    //         "stock" => "Jumlah barang",
    //         "color" => "Warna barang",
    //         "sku_id" => "Seri barang",
    //         "weight" => "Berat barang",
    //         "margin" => "Margin barang",
    //         "b_layanan" => "Biaya layanan barang",
    //         "hpp" => "Harga Pokok Penjualan barang",
    //         "category_id" => "Kategori barang",
    //         "subcategory_id" => "Subkategori barang",
    //         "description" => "Deskripsi barang",
    //     ]);

    //     // Validasi gambar (jika ada gambar baru)
    //     $request->validate([
    //         'front' => ['nullable', 'mimes:png,jpg,jpeg', 'max:5120'],
    //         'back' => ['nullable', 'mimes:png,jpg,jpeg', 'max:5120'],
    //         'left' => ['nullable', 'mimes:png,jpg,jpeg', 'max:5120'],
    //         'right' => ['nullable', 'mimes:png,jpg,jpeg', 'max:5120'],
    //         'detail1' => ['nullable', 'mimes:png,jpg,jpeg', 'max:5120'],
    //         'detail2' => ['nullable', 'mimes:png,jpg,jpeg', 'max:5120'],
    //         'detail3' => ['nullable', 'mimes:png,jpg,jpeg', 'max:5120'],
    //         'detail4' => ['nullable', 'mimes:png,jpg,jpeg', 'max:5120'],
    //     ]);

    //     try {
    //         // Ambil produk berdasarkan ID
    //         $produk = Products::findOrFail($id);

    //         // Update data produk
    //         $produk->name = $request->name;
    //         $produk->slug = $this->repository->sluggable($request->name);
    //         $produk->description = $request->description;
    //         $produk->category_id = $request->category_id;
    //         $produk->subcategory_id = $request->subcategory_id;
    //         $produk->weight = $request->weight;
    //         $produk->price = $request->price;
    //         $produk->color = $request->color;
    //         $produk->stock = $request->stock;
    //         $produk->hpp = $request->hpp;
    //         $produk->margin = $request->margin;
    //         $produk->b_layanan = $request->b_layanan;
    //         $produk->sku_id = $request->sku_id;
    //         $produk->updated_by = auth()->user()->id;
    //         $produk->save();

    //         // Sinkronisasi warna dan count pada tabel pivot
    //         $syncData = [];
    //         foreach ($countData as $key => $value) {
    //             $colorId = str_replace(['COL-', '-count'], '', $key);
    //             $syncData[$colorId] = ['count' => (int)$value];
    //         }
    //         $produk->colors()->sync($syncData);

    //         // Proses gambar jika ada file baru yang diunggah
    //         $positions = [
    //             'front' => 'pos-front',
    //             'back' => 'pos-back',
    //             'left' => 'pos-left',
    //             'right' => 'pos-right',
    //             'detail1' => 'pos-detail1',
    //             'detail2' => 'pos-detail2',
    //             'detail3' => 'pos-detail3',
    //             'detail4' => 'pos-detail4',
    //         ];

    //         foreach ($positions as $position => $pos_request_key) {
    //             $existingImage = $this->images_repository->getByPosition($id, $position);

    //             if ($request->$pos_request_key === null || $request->hasFile($position)) {
    //                 if ($existingImage) {
    //                     $oldImagePath = storage_path('app/public/' . $existingImage->name);
    //                     if (file_exists($oldImagePath)) {
    //                         unlink($oldImagePath);
    //                     }
    //                     $this->images_repository->destroy($existingImage->id);
    //                 }

    //                 if ($request->hasFile($position)) {
    //                     $imagePath = $request->file($position)->store('images', 'public');
    //                     $imageRecord = [
    //                         'id' => 'IPT-' . Helper::table_id(),
    //                         'product_id' => $id,
    //                         'position' => $position,
    //                         'name' => $imagePath,
    //                         'created_by' => auth()->user()->id,
    //                         'updated_by' => auth()->user()->id,
    //                     ];
    //                     $this->images_repository->store($imageRecord);
    //                 }
    //             }
    //         }

    //         return redirect()->route('product.index')->with('success', 'Produk berhasil diperbarui.');
    //     } catch (Exception $e) {
    //         if (env('APP_DEBUG')) {
    //             return $e->getMessage();
    //         }
    //         return back()->with('error', 'Terjadi kesalahan saat memperbarui produk.')->withInput();
    //     }
    // }

    public function update(Request $request, string $id)
    {
        // Dekripsi ID produk
        $id = Crypt::decryptString($id);

        // Ambil data input untuk warna dengan count lebih dari 0
        $allData = $request->all();
        $countData = array_filter($allData, function ($value, $key) {
            return str_ends_with($key, '-count') && (int)$value > 0;
        }, ARRAY_FILTER_USE_BOTH);

        // Ambil daftar warna
        $colorList = Color::select('id')->orderBy('name')->get();

        // Validasi data produk
        $data = $request->validate(array_merge([
            "name" => ['required', 'string', 'max:100'],
            "price" => ['required', 'string', 'max:25'],
            "weight" => ['required', 'string', 'max:6'],
            "color" => ['required', 'max:250'],
            "sku_id" => ['required', 'string', 'max:250'],
            "hpp" => ['required', 'string', 'max:25'],
            "margin" => ['required', 'string', 'max:3'],
            "b_layanan" => ['required', 'string', 'max:3'],
            "category_id" => ['required', 'string', 'max:250'],
            "subcategory_id" => ['required', 'string', 'max:250'],
            "description" => ['required', 'string'],
        ], array_combine(
            array_map(fn($colorId) => "COL-{$colorId}-count", $colorList->pluck('id')->toArray()),
            array_fill(0, count($colorList), ['integer', 'min:0'])
        )), [], [
            "name" => "Nama produk",
            "price" => "Harga produk",
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

        // Validasi gambar (jika ada gambar baru)
        $request->validate([
            'front' => ['nullable', 'mimes:png,jpg,jpeg', 'max:5120'],
            'back' => ['nullable', 'mimes:png,jpg,jpeg', 'max:5120'],
            'left' => ['nullable', 'mimes:png,jpg,jpeg', 'max:5120'],
            'right' => ['nullable', 'mimes:png,jpg,jpeg', 'max:5120'],
            'detail1' => ['nullable', 'mimes:png,jpg,jpeg', 'max:5120'],
            'detail2' => ['nullable', 'mimes:png,jpg,jpeg', 'max:5120'],
            'detail3' => ['nullable', 'mimes:png,jpg,jpeg', 'max:5120'],
            'detail4' => ['nullable', 'mimes:png,jpg,jpeg', 'max:5120'],
        ]);

        try {
            // Ambil produk berdasarkan ID
            $produk = Products::findOrFail($id);

            // Update data produk
            $produk->name = $request->name;
            $produk->slug = $this->repository->sluggable($request->name);
            $produk->description = $request->description;
            $produk->category_id = $request->category_id;
            $produk->subcategory_id = $request->subcategory_id;
            $produk->weight = $request->weight;
            $produk->price = $request->price;
            $produk->color = $request->color;
            $produk->stock = 0;
            $produk->hpp = $request->hpp;
            $produk->margin = $request->margin;
            $produk->b_layanan = $request->b_layanan;
            $produk->sku_id = $request->sku_id;
            $produk->updated_by = auth()->user()->id;
            $produk->save();

            // Sinkronisasi warna dan count pada tabel pivot
            $syncData = [];
            foreach ($countData as $key => $value) {
                $colorId = str_replace(['COL-', '-count'], '', $key);
                $syncData[$colorId] = ['count' => (int)$value];
            }
            $produk->colors()->sync($syncData);

            // Proses gambar jika ada file baru yang diunggah
            $positions = [
                'front' => 'pos-front',
                'back' => 'pos-back',
                'left' => 'pos-left',
                'right' => 'pos-right',
                'detail1' => 'pos-detail1',
                'detail2' => 'pos-detail2',
                'detail3' => 'pos-detail3',
                'detail4' => 'pos-detail4',
            ];

            foreach ($positions as $position => $pos_request_key) {
                $existingImage = $this->images_repository->getByPosition($id, $position);

                if ($request->$pos_request_key === null || $request->hasFile($position)) {
                    if ($existingImage) {
                        $oldImagePath = storage_path('app/public/' . $existingImage->name);
                        if (file_exists($oldImagePath)) {
                            unlink($oldImagePath);
                        }
                        $this->images_repository->destroy($existingImage->id);
                    }

                    if ($request->hasFile($position)) {
                        $imagePath = $request->file($position)->store('images', 'public');
                        $imageRecord = [
                            'id' => 'IPT-' . Helper::table_id(),
                            'product_id' => $id,
                            'position' => $position,
                            'name' => $imagePath,
                            'created_by' => auth()->user()->id,
                            'updated_by' => auth()->user()->id,
                        ];
                        $this->images_repository->store($imageRecord);
                    }
                }
            }

            return redirect()->route('product.index')->with('success', 'Produk berhasil diperbarui.');
        } catch (Exception $e) {
            if (env('APP_DEBUG')) {
                return $e->getMessage();
            }
            return back()->with('error', 'Terjadi kesalahan saat memperbarui produk.')->withInput();
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
