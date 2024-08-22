<?php

namespace App\Http\Controllers\Fo\home;

use App\Repositories\ContactUsRepository;
use App\Repositories\AboutUsRepository;
use App\Repositories\ProductRepository;
use App\Repositories\EventRepository;
use App\Repositories\TestimoniRepository;
use App\Repositories\PartnerRepository;
use App\Repositories\DiscountRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class BerandaController extends Controller
{
    private ContactUsRepository $contactUsRepository;
    private AboutUsRepository $aboutUsRepository;
    private ProductRepository $productRepository;
    private EventRepository $eventRepository;
    private TestimoniRepository $testimoniRepository;
    private PartnerRepository $partnerRepository;
    private DiscountRepository $discountRepository;

    protected $data = array();
    public function __construct(ContactUsRepository $contactUsRepository, AboutUsRepository $aboutUsRepository, ProductRepository $productRepository,  EventRepository $eventRepository, TestimoniRepository $testimoniRepository, PartnerRepository $partnerRepository, DiscountRepository $discountRepository)
    {
        $this->contactUsRepository = $contactUsRepository;
        $this->aboutUsRepository = $aboutUsRepository;
        $this->productRepository = $productRepository;
        $this->eventRepository = $eventRepository;
        $this->testimoniRepository = $testimoniRepository;
        $this->partnerRepository = $partnerRepository;
        $this->discountRepository = $discountRepository;

        $this->data['title'] = 'Home';
        $this->data['view_directory'] = "guest.feature.home";
    }

    public function index()
    {
        $ref = $this->data;
        $data['partners'] = $this->partnerRepository->getImage()
            ->map(function ($item) {
                $item['id'] = encrypt($item['id']);
                return $item;
            });
        $data['ptotal'] = $this->partnerRepository->getTotal();
        $data['contact'] = $this->contactUsRepository->getById('1');
        $data['about'] = $this->aboutUsRepository->getById('1');
        $data['products'] = $this->productRepository->getBeranda();
        $data['ptotal'] = $this->productRepository->getTotal();
        $data['events'] = $this->eventRepository->getBeranda();
        $data['etotal'] = $this->eventRepository->getTotal();
        $data['testimonis'] = $this->testimoniRepository->getAllFo();
        $data['etetotal'] = $this->testimoniRepository->getTotal();
        $data['discount_new'] = $this->discountRepository->getDiscNewCostumer()->toArray()['image_banner'];

        return view($this->data['view_directory'] . '.index', compact('ref', 'data'));
    }
}
