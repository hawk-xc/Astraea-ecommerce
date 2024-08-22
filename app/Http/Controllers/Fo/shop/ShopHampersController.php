<?php

namespace App\Http\Controllers\Fo\shop;

use App\Repositories\ContactUsRepository;
use App\Repositories\AboutUsRepository;
use App\Repositories\PartnerRepository;
use App\Repositories\CategoriesRepository;
use App\Repositories\SubCategoriesRepository;
use App\Repositories\HampersProductRepository;
use App\Repositories\UlasanRepository;
use App\Repositories\OrderHampersDetailRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class ShopHampersController extends Controller
{
    private ContactUsRepository $contactUsRepository;
    private AboutUsRepository $aboutUsRepository;
    private PartnerRepository $partnerRepository;
    private CategoriesRepository $categoriesRepository;
    private SubCategoriesRepository $subCategoriesRepository;
    private HampersProductRepository $productRepository;
    private UlasanRepository $ulasanRepository;
    private OrderHampersDetailRepository $orderHampersDetailRepository;
    protected $data = array();

    public function __construct(
        ContactUsRepository $contactUsRepository, 
        PartnerRepository $partnerRepository, 
        AboutUsRepository $aboutUsRepository, 
        CategoriesRepository $categoriesRepository, 
        SubCategoriesRepository $subCategoriesRepository, 
        HampersProductRepository $productRepository, 
        OrderHampersDetailRepository $orderHampersDetailRepository,
        UlasanRepository $ulasanRepository
    )
    {
        $this->contactUsRepository = $contactUsRepository;
        $this->aboutUsRepository = $aboutUsRepository;
        $this->partnerRepository = $partnerRepository;
        $this->categoriesRepository = $categoriesRepository;
        $this->subCategoriesRepository = $subCategoriesRepository;
        $this->productRepository = $productRepository;
        $this->orderHampersDetailRepository = $orderHampersDetailRepository;
        $this->ulasanRepository = $ulasanRepository;
        $this->data['title'] = 'Shop Hampers';
        $this->data['view_directory'] = "guest.feature.shop.hampers";
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
        return view($this->data['view_directory'] . '.index', compact('ref', 'data'));
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
        return view($this->data['view_directory'] . '.index', compact('ref', 'data'));
    }

    public function show(string $slug)
    {
        $ref = $this->data;
        $data['about'] = $this->aboutUsRepository->getById('1');
        $data['contact'] = $this->contactUsRepository->getById('1');
        $data['partners'] = $this->partnerRepository->getImage()
            ->map(function ($item) {
                $item['id'] = encrypt($item['id']);
                return $item;
            });
        $data['product'] = $this->productRepository->getBySlugFo($slug);
        $data['related_products'] = $this->productRepository->getRelatedProductFo($data['product']['category_id'], $slug);

        $data['cek_beli'] = $this->orderHampersDetailRepository->getCekBuy($data['product']['id']);
        $data['ulasans'] = $this->ulasanRepository->getAllFo($data['product']['id']);
        $data['avgrat'] = $this->ulasanRepository->getAvgRat($data['product']['id']);
        $data['product']['id'] = Crypt::encryptString($data['product']['id']);

        return view($this->data['view_directory'] . '.detail', compact('ref', 'data')); 
    }
}
