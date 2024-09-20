<?php

namespace App\Http\Controllers\Bo\Comprof;

use App\Repositories\AboutUsRepository;
use Exception;
use Helper;
use \App\Models\AboutUs as AboutModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\File;

class CProfileController extends Controller
{
    private AboutUsRepository $repository;
    protected $data = array();

    public function __construct(AboutUsRepository $repository)
    {
        $this->repository = $repository;
        $this->data['title'] = 'About Us';
        $this->data['view_directory'] = "admin.feature.comprof.about";
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ref = $this->data;
        $data = $this->repository->getById('1');
        $id = Crypt::encryptString(1);
        $ref["url"] = route("com_profile.update", $id);
        return view($this->data['view_directory'] . '.form', compact('ref', 'data'));
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
        // $id = Crypt::decryptString($id);
        $aboutus = AboutModel::find(1);

        $data = $request->validate([
            "title" => ['required', 'string', 'max:100'],
            "description" => ['required', 'string'],
            'image' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:5120'], // Mengubah 'image' menjadi 'file'
        ], [
            "title.required" => "Nama mitra harus di isi",
            "description.required" => "Deskripsi mitra harus di isi",
            'image.image' => "Berkas harus berupa gambar",
            'image.mimes' => "Berkas harus dalam format PNG, JPG, atau JPEG",
            'image.max' => "Berkas tidak boleh lebih dari 5MB",
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image'); // Mengakses file tertentu
            $image_path = $image->store('images', 'public'); // Menyimpan file ke folder 'public/image'

            if (File::exists(public_path($aboutus->image))) {
                File::delete(public_path($aboutus->image));
            }

            $image_path = $image_path;
        } else {
            $image_path = $aboutus->image;
        }

        try {
            $aboutus->title = $data["title"];
            $aboutus->description = $data["description"];
            $aboutus->updated_by = auth()->user()->id;
            $aboutus->image = $image_path;
            $aboutus->update();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('com_profile.index')->with('success', 'Berhasil mengubah About Us');

        // try {
        //     if (isset($data["image"])) {
        //         $old_image = $this->repository->getById($id)->image;
        //         if (isset($old_image)) {
        //             unlink(storage_path() . '/app/public/' . $old_image);
        //         }
        //         $image_path = $request->file('image')->store('images', 'public');
        //         $data["image"] =  $image_path;
        //     } else {
        //         unset($data["image"]);
        //     }
        //     $this->repository->edit($id, $data);
        //     return redirect()->route('com_profile.index')->with('success', 'Berhasil mengubah About Us');
        // } catch (Exception $e) {
        //     if (env('APP_DEBUG')) {
        //         return $e->getMessage();
        //     }
        //     return back()->with('error', "Oops..!! Terjadi keesalahan saat mengubah data")->withInput($request->input);
        // }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
