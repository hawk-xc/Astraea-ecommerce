<?php

namespace App\Http\Controllers\Bo\Product;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use \App\Models\Slider as SliderModel;
use Illuminate\Support\Facades\File;

class SliderProductController extends Controller
{
    protected $data = array();

    public function __construct()
    {
        $this->data['title'] = 'slider produk';
        $this->data['view_directory'] = "admin.feature.products.sliders";
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sliders = SliderModel::all();
        $ref = $this->data;
        return view($this->data['view_directory'] . '.index', compact('ref', 'sliders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $ref = $this->data;

        $ref["url"] = route("slider.store");
        return view($this->data['view_directory'] . '.form', compact('ref'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            "slider_title" => ['required', 'string', 'max:100'],
            "view" => ['required', 'string', 'max:25'],
            "button_title" => ['nullable', 'string', 'max:255'],
            "button_link" => ['nullable', 'string', 'max:255'],
            "button_background" => ['nullable', 'string', 'max:255'],
            "button_text_color" => ['nullable', 'string', 'max:255'],
            "horizontal" => ['nullable', 'string', 'max:255'],
            "vertical" => ['nullable', 'string', 'max:250'],
        ]);


        $request->validate([
            'image' => ['required', 'mimes:png,jpg,jpeg', 'max:10120'],
        ]);

        // mengecek gambar diinput
        // menambahkan gambar ke folder storage public
        if ($request->hasFile('image')) {
            $image = $request->file('image'); // Mengakses file tertentu
            $image_path = $image->store('images', 'public'); // Menyimpan file ke folder 'public/image'
        } else {
            return redirect()->back()->with(
                'error',
                'Anda harus mengupload gambar terlebih dahulu'
            );
        }

        try {
            $slider = new SliderModel();
            $slider->title = $data["slider_title"];
            $slider->view = $data["view"];
            $slider->button_title = $data["button_title"];
            $slider->button_link = $data["button_link"];
            $slider->image = 'storage/' . $image_path;
            $slider->button_background = $data["button_background"];
            $slider->button_text_color = $data["button_text_color"];
            $slider->button_horizontal_layout = $data["horizontal"];
            $slider->button_vertical_layout = 'top';
            $slider->save();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('slider.index')->with('success', 'Berhasil menambah slider');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $ref = $this->data;
        $slider = SliderModel::findOrFail($id);

        $ref["url"] = route("slider.update", $id);
        // dd($slider);
        return view($this->data['view_directory'] . '.form', compact('ref', 'slider'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $slider = SliderModel::findOrFail($id);

        $data = $request->validate([
            "slider_title" => ['required', 'string', 'max:100'],
            "view" => ['required', 'string', 'max:25'],
            "button_title" => ['nullable', 'string', 'max:255'],
            "button_link" => ['nullable', 'string', 'max:255'],
            "button_background" => ['nullable', 'string', 'max:255'],
            "button_text_color" => ['nullable', 'string', 'max:255'],
            "horizontal" => ['nullable', 'string', 'max:255'],
            "vertical" => ['nullable', 'string', 'max:250'],
        ]);

        $request->validate([
            'image' => ['nullable', 'mimes:png,jpg,jpeg', 'max:10120'],
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image'); // Mengakses file tertentu
            $image_path = $image->store('images', 'public'); // Menyimpan file ke folder 'public/image'


            $imagePath = public_path($slider->image);

            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }

            $image_path = 'storage/' . $image_path;
        } else {
            $image_path = $slider->image;
        }

        try {
            $slider->title = $data["slider_title"];
            $slider->view = $data["view"];
            $slider->button_title = $data["button_title"];
            $slider->button_link = $data["button_link"];
            $slider->image = $image_path;
            $slider->button_background = $data["button_background"];
            $slider->button_text_color = $data["button_text_color"];
            $slider->button_horizontal_layout = $data["horizontal"];
            $slider->button_vertical_layout = 'top';
            $slider->update();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('slider.index')->with('success', 'Berhasil menambah slider');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $slider = SliderModel::findOrFail($id);

        $imagePath = public_path($slider->image);

        if (File::exists($imagePath)) {
            File::delete($imagePath);
        }

        $slider->delete();

        return redirect()->route('slider.index')->with('success', 'Slider dan gambar berhasil dihapus.');
    }
}
