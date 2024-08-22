<?php

namespace App\Http\Controllers\Fo\partner;

use App\Repositories\ContactUsRepository;
use App\Repositories\PartnerRepository;
use App\Repositories\AboutUsRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;

class PartnerController extends Controller
{
    private ContactUsRepository $contactUsRepository;
    private AboutUsRepository $aboutUsRepository;
    private PartnerRepository $partnerRepository;
    protected $data = array();

    public function __construct(ContactUsRepository $contactUsRepository, PartnerRepository $partnerRepository, AboutUsRepository $aboutUsRepository)
    {
        $this->contactUsRepository = $contactUsRepository;
        $this->partnerRepository = $partnerRepository;
        $this->aboutUsRepository = $aboutUsRepository;
        $this->data['title'] = 'Partner';
        $this->data['view_directory'] = "guest.feature.partner";
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function show(string $id)
    {
        $ref = $this->data;
        $id = Crypt::decrypt($id);
        $data['partner'] = $this->partnerRepository->getById($id);
        $data['about'] = $this->aboutUsRepository->getById('1');
        $data['contact'] = $this->contactUsRepository->getById('1');
        return view($this->data['view_directory'] . '.index', compact('ref', 'data'));
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
