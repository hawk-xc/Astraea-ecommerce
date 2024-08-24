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

    public function __construct(ContactUsRepository $contactUsRepository,
    PartnerRepository $partnerRepository,
    AboutUsRepository $aboutUsRepository,
    CategoriesRepository $categoriesRepository,
    SubCategoriesRepository $subCategoriesRepository,
    ProductRepository $productRepository,
    UlasanRepository $ulasanRepository,
    OrderDetailRepository $orderDetailRepository)
    {
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
        // dd($data['products']->toArray());
        
        return view($this->data['view_directory'] . '.index', compact('ref', 'data'));
    }

    public function refresh(Request $request) {
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
        return view($this->data['view_directory'] . '.index', compact('ref', 'data'));
    }

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
        $data['product'] = $this->productRepository->getBySlugFo($name);
        $data['related_products'] = $this->productRepository->getRelatedProductFo($data['product']['category_id'], $name);

        $data['cek_beli'] = $this->orderDetailRepository->getCekBuy($data['product']['id']);
        $data['ulasans'] = $this->ulasanRepository->getAllFo($data['product']['id']);
        $data['avgrat'] = $this->ulasanRepository->getAvgRat($data['product']['id']);
        $data['product']['id'] = Crypt::encryptString($data['product']['id']);

        // dd($data["product"]);
        return view($this->data['view_directory'] . '.detail', compact('ref', 'data'));
    }
}
