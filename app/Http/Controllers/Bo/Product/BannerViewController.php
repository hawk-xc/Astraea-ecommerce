<?php

namespace App\Http\Controllers\Bo\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BannerView as BannerModel;

class BannerViewController extends Controller
{
    protected $data = array();

    public function __construct()
    {
        $this->data['title'] = 'Banner';
        $this->data['view_directory'] = "admin.feature.products.banner";
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $banner = BannerModel::first()->get();

        $ref = $this->data;
        return view($this->data['view_directory'] . '.index', compact('ref', 'banner'));
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
}
