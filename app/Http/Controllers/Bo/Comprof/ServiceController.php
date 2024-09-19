<?php

namespace App\Http\Controllers\Bo\Comprof;

use App\Repositories\ServiceRepository;
use Exception;
use Helper;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use \App\Models\Service as ServiceModel;
use Illuminate\Support\Facades\File;

class ServiceController extends Controller
{
    private ServiceRepository $repository;
    protected $data = array();

    public function __construct(ServiceRepository $repository)
    {
        $this->repository = $repository;
        $this->data['title'] = 'Service';
        $this->data['view_directory'] = "admin.feature.comprof.service";
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ref = $this->data;
        $ref['services'] = ServiceModel::get();
        return view($this->data['view_directory'] . '.index', compact('ref'));
    }

    public function datas(Request $request)
    {
        if ($request->ajax()) {
            try {
                $data = $this->repository->getAll();
            } catch (Exception $e) {
                dd($e);
            }
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $row["id"] = Crypt::encryptString($row["id"]);
                    $btn = '<form method="POST" action="' . route('service.destroy', $row["id"]) . '">
                                        ' . method_field("DELETE") . '
                                        ' . csrf_field() . '
                                        <a href="' . route("service.edit", $row["id"]) . '" class="btn bg-gradient-info btn-tooltip"><i class="bi bi-pencil-square"></i></a>
                                        <button type="button" id="deleteRow" data-message="' . $row["name"] . '" class="btn bg-gradient-danger btn-tooltip show-alert-delete-box" data-toggle="tooltip" title="Delete"><i class="bi bi-trash"></i></button>
                                    </form>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function show(string $slug)
    {
        dd($slug);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $ref = $this->data;
        $ref["url"] = route("service.store");
        return view($this->data['view_directory'] . '.form', compact('ref'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            "name" => ['required', 'string', 'max:100'],
            "description" => ['required', 'string'],
            'image' => ['required', 'image', 'mimes:png,jpg,jpeg', 'max:5120'],
        ], [
            "name.required" => "Nama service harus di isi",
            "description.required" => "Deskripsi service harus di isi",
            'image.required' => "Gambar harus diisi",
            'image.image' => "Berkas harus berupa gambar",
            'image.mimes' => "Berkas harus dalam format PNG, JPG, atau JPEG",
            'image.max' => "Berkas tidak boleh lebih dari 5MB",
        ]);

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
            $services = new ServiceModel;
            $services->name = $data["name"];
            $services->description = $data["description"];
            $services->slug = Str::slug($data['name']);
            $services->image = 'storage/' . $image_path;
            $services->created_by = auth()->user()->id;
            $services->updated_by = auth()->user()->id;
            $services->save();

            return redirect()->route('service.index')->with('success', 'Data service berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $slug)
    {
        $ref = $this->data;
        $service = ServiceModel::where('slug', $slug)->first();
        // $id = Crypt::encryptString($id);
        // $data = $this->repository->getById($id);
        // $id = Crypt::decryptString($id);
        $ref["url"] = route("service.update", $slug);
        return view($this->data['view_directory'] . '.form', compact('ref', "service"));
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, string $slug)
    {
        $service = ServiceModel::where('slug', $slug)->get()->first();

        $data = $request->validate([
            "name" => ['required', 'string', 'max:100'],
            "description" => ['required', 'string'],
            'image' => ['image', 'mimes:png,jpg,jpeg', 'max:5120'],
        ], [
            "name.required" => "Nama service harus di isi",
            "description.required" => "Deskripsi service harus di isi",
            'image.required' => "Gambar harus diisi",
            'image.image' => "Berkas harus berupa gambar",
            'image.mimes' => "Berkas harus dalam format PNG, JPG, atau JPEG",
            'image.max' => "Berkas tidak boleh lebih dari 5MB",
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image'); // Mengakses file tertentu
            $image_path = $image->store('images', 'public'); // Menyimpan file ke folder 'public/image'


            $imagePath = public_path($service->image);

            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }

            $image_path = 'storage/' . $image_path;
        } else {
            $image_path = $service->image;
        }

        try {
            $service->name = $data['name'];
            $service->description = $data['description'];
            $service->slug = Str::slug($data['name']);
            $service->image = $image_path;
            $service->update();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('service.index')->with('success', 'Berhasil update service baru');
    }

    // public function update(Request $request, string $id)
    // {
    //     $id = Crypt::decryptString($id);
    //     $data = $request->validate([
    //         "name" => ['required', 'string', 'max:100'],
    //         "description" => ['required', 'string'],
    //         'image' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:5120'],
    //     ], [
    //         "name.required" => "Nama service harus di isi",
    //         "description.required" => "Deskripsi service harus di isi",
    //         'image.image' => "Berkas harus berupa gambar",
    //         'image.mimes' => "Berkas harus dalam format PNG, JPG, atau JPEG",
    //         'image.max' => "Berkas tidak boleh lebih dari 5MB",
    //     ]);

    //     $data['updated_by'] = auth()->user()->id;
    //     $data['slug'] = Str::slug($data['name']); // Menambahkan slug

    //     try {
    //         if ($request->hasFile('image')) {
    //             $old_image = $this->repository->getById($id)->image;
    //             unlink(storage_path() . '/app/public/' . $old_image);
    //             $image_path = $request->file('image')->store('images', 'public');
    //             $data["image"] = $image_path;
    //         } else {
    //             unset($data["image"]);
    //         }
    //         $this->repository->edit($id, $data);
    //         return redirect()->route('service.index')->with('success', 'Berhasil mengubah service ' . $data["name"]);
    //     } catch (Exception $e) {
    //         if (env('APP_DEBUG')) {
    //             return $e->getMessage();
    //         }
    //         return back()->with('error', "Oops..!! Terjadi kesalahan saat mengubah data")->withInput($request->input());
    //     }
    // }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $id = Crypt::decryptString($id);
        $image = $this->repository->getById($id);
        $image_path = storage_path() . '/app/public/' . $image->image;
        try {
            unlink($image_path);
            $this->repository->destroy($id);
            return redirect()->route('service.index')->with('success', 'Service berhasil dihapus');
        } catch (\Exception $e) {
            if (env('APP_DEBUG')) {
                return $e->getMessage();
            }
            return back()->with('error', 'Terjadi kesalahan saat menghapus service');
        }
    }
}
