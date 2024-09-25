<?php

namespace App\Http\Controllers\fo\about;

use App\Repositories\ContactUsRepository;
use App\Repositories\PartnerRepository;
use App\Repositories\CertificateRepository;
use App\Repositories\AboutUsRepository;
use App\Repositories\ServiceRepository;
use App\Repositories\TestimoniRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use \App\Models\Service as ServiceModel;
use \App\Models\BannerView as BannerModel;

class AboutUsController extends Controller
{
    private ContactUsRepository $contactUsRepository;
    private PartnerRepository $partnerRepository;
    private CertificateRepository $certificateRepository;
    private AboutUsRepository $aboutUsRepository;
    private ServiceRepository $serviceRepository;
    private TestimoniRepository $testimoniRepository;

    protected $data = array();
    public function __construct(ContactUsRepository $contactUsRepository, PartnerRepository $partnerRepository, CertificateRepository $certificateRepository, AboutUsRepository $aboutUsRepository, ServiceRepository $serviceRepository, TestimoniRepository $testimoniRepository)
    {
        $this->contactUsRepository = $contactUsRepository;
        $this->partnerRepository = $partnerRepository;
        $this->certificateRepository = $certificateRepository;
        $this->aboutUsRepository = $aboutUsRepository;
        $this->serviceRepository = $serviceRepository;
        $this->testimoniRepository = $testimoniRepository;
        $this->data['title'] = 'About Us';
        $this->data['view_directory'] = "guest.feature.about";
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ref = $this->data;
        $data['partners'] = $this->partnerRepository->getImage()
            ->map(function ($item) {
                $item['id'] = encrypt($item['id']);
                return $item;
            });
        $data['certificates'] = $this->certificateRepository->getImage()
            ->map(function ($item) {
                $item['id'] = encrypt($item['id']);
                return $item;
            });
        $data['about'] = $this->aboutUsRepository->getById('1');
        $data['contact'] = $this->contactUsRepository->getById('1');
        $data['services'] = ServiceModel::get();

        $data['ptotal'] = $this->partnerRepository->getTotal();
        $data['ctotal'] = $this->certificateRepository->getTotal();
        $data['stotal'] = $this->serviceRepository->getTotal();

        $data['testimonis'] = $this->testimoniRepository->getAllFo();
        $data['etetotal'] = $this->testimoniRepository->getTotal();

        $data['banner'] = BannerModel::first()->pluck('images');

        return view($this->data['view_directory'] . '.index', compact('ref', 'data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $service = ServiceModel::where('slug', $slug)->firstOrFail();
        // Set the view directory for the 'show' view
        $ref = $this->data;

        $this->data['title'] = 'About Us';

        $this->data['view_directory'] = "guest.feature.about";

        $data['partners'] = $this->partnerRepository->getImage()
            ->map(function ($item) {
                $item['id'] = encrypt($item['id']);
                return $item;
            });
        $data['certificates'] = $this->certificateRepository->getImage()
            ->map(function ($item) {
                $item['id'] = encrypt($item['id']);
                return $item;
            });
        $data['about'] = $this->aboutUsRepository->getById('1');
        $data['contact'] = $this->contactUsRepository->getById('1');
        $data['services'] = ServiceModel::get();

        $data['ptotal'] = $this->partnerRepository->getTotal();
        $data['ctotal'] = $this->certificateRepository->getTotal();
        $data['stotal'] = $this->serviceRepository->getTotal();

        $data['testimonis'] = $this->testimoniRepository->getAllFo();
        $data['etetotal'] = $this->testimoniRepository->getTotal();

        $data['banner'] = BannerModel::first()->pluck('images');

        // Return the view and pass the data for the service
        return view($this->data['view_directory'] . '.show', compact('ref', 'service', 'data'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

