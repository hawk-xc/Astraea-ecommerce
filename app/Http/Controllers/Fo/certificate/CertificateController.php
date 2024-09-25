<?php

namespace App\Http\Controllers\Fo\certificate;

use App\Repositories\ContactUsRepository;
use App\Repositories\CertificateRepository;
use App\Repositories\AboutUsRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;

class CertificateController extends Controller
{
    private ContactUsRepository $contactUsRepository;
    private CertificateRepository $certificateRepository;
    private AboutUsRepository $aboutUsRepository;
    protected $data = array();

    public function __construct(ContactUsRepository $contactUsRepository, CertificateRepository $certificateRepository, AboutUsRepository $aboutUsRepository)
    {
        $this->contactUsRepository = $contactUsRepository;
        $this->certificateRepository = $certificateRepository;
        $this->aboutUsRepository = $aboutUsRepository;
        $this->data['title'] = 'Certificate';
        $this->data['view_directory'] = "guest.feature.certificate";
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
        $data['certificate'] = $this->certificateRepository->getById($id);
        $data['contact'] = $this->contactUsRepository->getById('1');
        $data['about'] = $this->aboutUsRepository->getById('1');
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
