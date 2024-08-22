<?php

namespace App\Http\Controllers\Fo\customer;

use App\Repositories\ContactUsRepository;
use App\Repositories\AboutUsRepository;
use App\Repositories\DiscountCostumerRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Controller;

class CouponListController extends Controller
{

    private ContactUsRepository $contactUsRepository;
    private AboutUsRepository $aboutUsRepository;
    private DiscountCostumerRepository $discountCostumerRepository;
    protected $data = array();

    public function __construct(ContactUsRepository $contactUsRepository, AboutUsRepository $aboutUsRepository, DiscountCostumerRepository $discountCostumerRepository)
    {
        $this->contactUsRepository = $contactUsRepository;
        $this->aboutUsRepository = $aboutUsRepository;
        $this->discountCostumerRepository = $discountCostumerRepository;
        $this->data['title'] = 'Coupon Promo';
        $this->data['view_directory'] = "guest.feature.coupon";
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {   
        $ref = $this->data;
        $data['contact'] = $this->contactUsRepository->getById('1');
        $data['about'] = $this->aboutUsRepository->getById('1');
        $data['coupon'] = $this->discountCostumerRepository->getUser(auth()->guard('customer')->user()->id);
        // dd($data);
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
    public function show(string $id)
    {
        //
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
