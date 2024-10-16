<?php

namespace App\Http\Controllers\Fo\shop;

use App\Repositories\ContactUsRepository;
use App\Repositories\AboutUsRepository;
use App\Repositories\PartnerRepository;
use App\Repositories\CategoriesRepository;
use App\Repositories\SubCategoriesRepository;
use App\Repositories\HampersProductRepository;
use App\Repositories\UlasanRepository;
use App\Repositories\OrderDetailRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Models\BannerView as BannerModel;
use App\Models\SubCategories;

class HampersSearchController extends Controller
{

    private ContactUsRepository $contactUsRepository;
    private HampersProductRepository $HampersProductRepository;
    private AboutUsRepository $aboutUsRepository;
    private PartnerRepository $partnerRepository;
    private CategoriesRepository $categoriesRepository;
    private SubCategoriesRepository $subCategoriesRepository;
    private UlasanRepository $ulasanRepository;
    private OrderDetailRepository $orderDetailRepository;
    protected $data = array();

    public function __construct(
        ContactUsRepository $contactUsRepository,
        PartnerRepository $partnerRepository,
        AboutUsRepository $aboutUsRepository,
        HampersProductRepository $HampersProductRepository,
        CategoriesRepository $categoriesRepository,
        SubCategoriesRepository $subCategoriesRepository,
        UlasanRepository $ulasanRepository,
        OrderDetailRepository $orderDetailRepository
    ) {
        $this->contactUsRepository = $contactUsRepository;
        $this->aboutUsRepository = $aboutUsRepository;
        $this->HampersProductRepository = $HampersProductRepository;
        $this->partnerRepository = $partnerRepository;
        $this->categoriesRepository = $categoriesRepository;
        $this->subCategoriesRepository = $subCategoriesRepository;
        $this->ulasanRepository = $ulasanRepository;
        $this->orderDetailRepository = $orderDetailRepository;
        $this->data['title'] = 'Shop Hampers';
        $this->data['view_directory'] = "guest.feature.shop.hampers";

        $this->data['banner'] = BannerModel::first()->pluck('images');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $name = $request->name;
        // dd($name);
        $ref = $this->data;
        $data['about'] = $this->aboutUsRepository->getById('1');
        $data['contact'] = $this->contactUsRepository->getById('1');
        $data['partners'] = $this->partnerRepository->getImage()
            ->map(function ($item) {
                $item['id'] = encrypt($item['id']);
                return $item;
            });
        $data['categories'] = $this->categoriesRepository->getAllFo();
        $data['products'] = $this->HampersProductRepository->getSearchHampers($name);
        $data['subcategories'] = SubCategories::select('name')->distinct()->get();
        $data['searchparameter'] = $name;

        $data['banner'] = BannerModel::first()->pluck('images');

        return view($this->data['view_directory'] . '.index', compact('ref', 'data'));
    }
}
