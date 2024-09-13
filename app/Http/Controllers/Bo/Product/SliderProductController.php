<?php

namespace App\Http\Controllers\Bo\Product;

use App\Repositories\HampersProductRepository;
use App\Repositories\HampersImageProductRepository;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SliderProductController extends Controller
{
    private HampersProductRepository $repository;
    private HampersImageProductRepository $images_repository;

    protected $data = array();

    public function __construct(HampersProductRepository $repository, HampersImageProductRepository $images_repository)
    {
        $this->repository = $repository;
        $this->images_repository = $images_repository;
        $this->data['title'] = 'slider produk';
        $this->data['view_directory'] = "admin.feature.products.sliders";
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ref = $this->data;
        return view($this->data['view_directory'] . '.index', compact('ref'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $ref = $this->data;

        if (old('color')) {
            $array_color = array_flip(old('color'));
        } else {
            $array_color = [];
        }

        $ref["url"] = route("slider.store");
        return view($this->data['view_directory'] . '.form', compact('ref', 'array_color'));
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
