<?php

namespace App\Http\Controllers\Fo\shop;

use App\Repositories\ContactUsRepository;
use App\Repositories\AboutUsRepository;
use App\Repositories\PartnerRepository;
use App\Repositories\CategoriesRepository;
use App\Repositories\SubCategoriesRepository;
use App\Repositories\ProductRepository;
use App\Repositories\UlasanRepository;
use App\Repositories\OrderDetailRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Models\BannerView as BannerModel;
use App\Models\SubCategories;

class ShopProductController extends Controller
{

    private ContactUsRepository $contactUsRepository;
    private AboutUsRepository $aboutUsRepository;
    private PartnerRepository $partnerRepository;
    private CategoriesRepository $categoriesRepository;
    private SubCategoriesRepository $subCategoriesRepository;
    private ProductRepository $productRepository;
    private UlasanRepository $ulasanRepository;
    private OrderDetailRepository $orderDetailRepository;
    protected $data = array();

    public function __construct(
        ContactUsRepository $contactUsRepository,
        PartnerRepository $partnerRepository,
        AboutUsRepository $aboutUsRepository,
        CategoriesRepository $categoriesRepository,
        SubCategoriesRepository $subCategoriesRepository,
        ProductRepository $productRepository,
        UlasanRepository $ulasanRepository,
        OrderDetailRepository $orderDetailRepository
    ) {
        $this->contactUsRepository = $contactUsRepository;
        $this->aboutUsRepository = $aboutUsRepository;
        $this->partnerRepository = $partnerRepository;
        $this->categoriesRepository = $categoriesRepository;
        $this->subCategoriesRepository = $subCategoriesRepository;
        $this->productRepository = $productRepository;
        $this->ulasanRepository = $ulasanRepository;
        $this->orderDetailRepository = $orderDetailRepository;
        $this->data['title'] = 'Shop Product';
        $this->data['view_directory'] = "guest.feature.shop.product";

        $this->data['banner'] = BannerModel::first()->pluck('images');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ref = $this->data;
        $data['about'] = $this->aboutUsRepository->getById('1');
        $data['contact'] = $this->contactUsRepository->getById('1');
        $data['partners'] = $this->partnerRepository->getImage()
            ->map(function ($item) {
                $item['id'] = encrypt($item['id']);
                return $item;
            });
        $data['categories'] = $this->categoriesRepository->getAllFo();
        $data['products'] = $this->productRepository->getAllFo();
        $data['subcategories'] = SubCategories::select('name')->distinct()->get();

        $data['banner'] = BannerModel::first()->pluck('images');

        return view($this->data['view_directory'] . '.index', compact('ref', 'data'));
    }

    public function search(string $name)
    {
        dd($name);
        // search all product
        $ref = $this->data;
        $data['about'] = $this->aboutUsRepository->getById('1');
        $data['contact'] = $this->contactUsRepository->getById('1');
        $data['partners'] = $this->partnerRepository->getImage()
            ->map(function ($item) {
                $item['id'] = encrypt($item['id']);
                return $item;
            });
        $data['categories'] = $this->categoriesRepository->getAllFo();
        $data['products'] = $this->productRepository->getAllFo();
        $data['subcategories'] = SubCategories::select('name')->distinct()->get();

        $data['banner'] = BannerModel::first()->pluck('images');

        return view($this->data['view_directory'] . '.search', compact('ref', 'data'));
    }

    public function refresh(Request $request)
    {
        $data = $this->productRepository->get_by_color(decrypt($request->name), $request->color_id);
        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function categoryShow(string $idCategory)
    {
        $ref = $this->data;
        $data['about'] = $this->aboutUsRepository->getById('1');
        $data['contact'] = $this->contactUsRepository->getById('1');
        $data['partners'] = $this->partnerRepository->getImage()
            ->map(function ($item) {
                $item['id'] = encrypt($item['id']);
                return $item;
            });
        $data['idCategory'] = $idCategory;
        $data['categories'] = $this->categoriesRepository->getAllFo();
        $data['subcategories'] = $this->subCategoriesRepository->getCate($idCategory);
        $data['products'] = $this->productRepository->getAllCatgoryFo($idCategory);
        // dd($data);
        $data['banner'] = BannerModel::first()->pluck('images');

        return view($this->data['view_directory'] . '.index', compact('ref', 'data'));
    }

    public function showsubcategories(string $name)
    { {
            $ref = $this->data;
            $data['about'] = $this->aboutUsRepository->getById('1');
            $data['contact'] = $this->contactUsRepository->getById('1');
            $data['partners'] = $this->partnerRepository->getImage()
                ->map(function ($item) {
                    $item['id'] = encrypt($item['id']);
                    return $item;
                });
            $data['categories'] = $this->categoriesRepository->getAllFo();

            $data['products'] = [];

            $data['subcategories'] = SubCategories::select('name')->distinct()->get();
            $data['subcategoriesid'] = SubCategories::select('id')->distinct()->get();

            $data['subcategoriesitem'] = SubCategories::where('name', 'like', '%' . $name . '%')->get();

            // dd($data['subcategoriesitem']);
            foreach ($data['subcategoriesitem'] as $productcategories) {
                if ($productcategories !== null) {
                    // $data['products'][] = $productcategories->product()->get();
                    foreach ($productcategories->product()->orderBy('stock', 'DESC')->get() as $productitem) {
                        $data['products'][] = $productitem;
                    }
                }
            }

            // foreach ($data['subcategoriesitem'] as $productcategories) {
            //     if ($productcategories !== null) {
            //         foreach ($productcategories->product as $product) {
            //             $data['products'][] = $product; // Tambahkan produk ke array tanpa menimpa
            //         }
            //     }
            // }

            // dd($data['products']);

            $data['banner'] = BannerModel::first()->pluck('images');

            return view($this->data['view_directory'] . '.subcategoryindex', compact('ref', 'data'));
        }
    }

    // public function show(string $name)
    // {
    //     $ref = $this->data;
    //     $data['about'] = $this->aboutUsRepository->getById('1');
    //     $data['contact'] = $this->contactUsRepository->getById('1');
    //     $data['partners'] = $this->partnerRepository->getImage()
    //         ->map(function ($item) {
    //             $item['id'] = encrypt($item['id']);
    //             return $item;
    //         });
    //     $data['product'] = $this->productRepository->getBySlugFo($name);
    //     $data['product_total_count'] = $data['product']->product_colors->sum('count');
    //     $data['related_products'] = $this->productRepository->getRelatedProductFo($data['product']['category_id'], $name);

    //     $data['cek_beli'] = $this->orderDetailRepository->getCekBuy($data['product']['id']);
    //     $data['ulasans'] = $this->ulasanRepository->getAllFo($data['product']['id']);
    //     $data['avgrat'] = $this->ulasanRepository->getAvgRat($data['product']['id']);
    //     $data['product']['id'] = Crypt::encryptString($data['product']['id']);

    //     $data['banner'] = BannerModel::first()->pluck('images');

    //     // dd($data["product"]);
    //     return view($this->data['view_directory'] . '.detail', compact('ref', 'data'));
    // }

    public function show(string $name)
    {
        $ref = $this->data;
        $data['about'] = $this->aboutUsRepository->getById('1');
        $data['contact'] = $this->contactUsRepository->getById('1');
        $data['partners'] = $this->partnerRepository->getImage()
            ->map(function ($item) {
                $item['id'] = encrypt($item['id']);
                return $item;
            });

        // Mendapatkan produk dari repository
        $data['product'] = $this->productRepository->getBySlugFo($name);

        // Menghitung total count dari relasi product_colors di controller
        $data['product_total_count'] = $data['product']->product_colors->sum('count');

        // Mendapatkan daftar warna dari relasi product_colors
        $data['product_colors'] = $data['product']->product_colors->map(function ($productColor) {
            $color = \App\Models\Color::find('COL-' . $productColor->color_id);

            return [
                'id' => $productColor->color_id,
                'name' => $color ? $color->name : 'Tidak diketahui',
                // 'name' => optional($productColor->color)->name, // Pastikan Anda memiliki relasi 'color' di ProductColor
                'count' => $productColor->count,
            ];
        });

        $data['related_products'] = $this->productRepository->getRelatedProductFo($data['product']['category_id'], $name);
        $data['cek_beli'] = $this->orderDetailRepository->getCekBuy($data['product']['id']);
        $data['ulasans'] = $this->ulasanRepository->getAllFo($data['product']['id']);
        $data['avgrat'] = $this->ulasanRepository->getAvgRat($data['product']['id']);
        $data['product']['id'] = Crypt::encryptString($data['product']['id']);
        $data['banner'] = BannerModel::first()->pluck('images');

        return view($this->data['view_directory'] . '.detail', compact('ref', 'data'));
    }
}
