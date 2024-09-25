<?php

namespace App\Http\Controllers\Bo\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BannerView as BannerModel;
use Illuminate\Support\Facades\File;

class BannerViewController extends Controller
{
    protected $data = array();

    public function __construct()
    {
        $this->data['title'] = 'Banner';
        $this->data['view_directory'] = "admin.feature.banner";
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $banner = BannerModel::first();
        $ref = $this->data;
        $ref["url"] = route("banner.update", isset($banner) ? $banner->id : 1);

        return view($this->data['view_directory'] . '.index', compact('ref', 'banner'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $banner = BannerModel::find($id);

        $request->validate([
            'image' => ['nullable', 'mimes:png,jpg,jpeg', 'max:10120'],
        ]);

        if (isset($banner)) {
            if ($request->hasFile('image')) {
                $image = $request->file('image'); // Mengakses file tertentu
                $image_path = $image->store('images', 'public'); // Menyimpan file ke folder 'public/image'


                $imagePath = public_path($banner->images);

                if (File::exists($imagePath)) {
                    File::delete($imagePath);
                }

                $image_path = 'storage/' . $image_path;

                $banner->images = $image_path;
                $banner->update();
            } else {
                $image_path = $banner->images;
            }
        } else {
            $image = $request->file('image'); // Mengakses file tertentu
            $image_path = $image->store('images', 'public'); // Menyimpan file ke folder 'public/image'

            $image_path = 'storage/' . $image_path;

            $banner = new BannerModel();
            $banner->images = $image_path;
            $banner->save();
        }

        return redirect()->route('banner.index')->with('success', 'Berhasil mengubah banner');
    }
}
