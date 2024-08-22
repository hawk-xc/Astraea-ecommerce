<?php

namespace App\Http\Controllers\Bo\Comprof;

use App\Repositories\PartnerRepository;
use Exception;
use Helper;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;

class PartnerController extends Controller
{
    private PartnerRepository $repository;
    protected $data = array();

    public function __construct(PartnerRepository $repository)
    {
        $this->repository = $repository;
        $this->data['title'] = 'Partner';
        $this->data['view_directory'] = "admin.feature.comprof.partner";
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ref = $this->data;
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
                    $btn = '<form method="POST" action="' . route('partner.destroy', $row["id"]) . '">
                                        ' . method_field("DELETE") . '
                                        ' . csrf_field() . '
                                        <a href="' . route("partner.edit", $row["id"]) . '" class="btn bg-gradient-info btn-tooltip"><i class="bi bi-pencil-square"></i></a>
                                        <button type="button" id="deleteRow" data-message="' . $row["name"] . '" class="btn bg-gradient-danger btn-tooltip show-alert-delete-box" data-toggle="tooltip" title="Delete"><i class="bi bi-trash"></i></button>
                                    </form>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $ref = $this->data;
        $ref["url"] = route("partner.store");
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
            'image' => ['required', 'image', 'mimes:png,jpg,jpeg', 'max:5120'], // Mengubah 'image' menjadi 'file'
        ],[
            "name.required" => "Nama mitra harus di isi",
            "description.required" => "Deskripsi mitra harus di isi",
            'image.required' => "Logo harus diisi",
            'image.image' => "Berkas harus berupa gambar",
            'image.mimes' => "Berkas harus dalam format PNG, JPG, atau JPEG",
            'image.max' => "Berkas tidak boleh lebih dari 5MB",
        ]);

        $data['created_by'] = auth()->user()->id;
        $data['updated_by'] = auth()->user()->id;

        try
        {
            $image_path = $request->file('image')->store('images', 'public'); // Menggunakan 'image' sebagai nama field file
            //save image
            $data["image"] = $image_path;
            //proses save
            $this->repository->store($data);
            return redirect()->route('partner.index')->with('success', 'Berhasil menambah partner ' . $data["name"]);
        }
        catch (Exception $e)
        {
            if (env('APP_DEBUG'))
            {
                return $e->getMessage();
            }
            return back()->with('error', "Oops..!! Terjadi keesalahan saat menyimpan data")->withInput($request->input);
        }
    
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
        $id = Crypt::decryptString($id);
        $ref = $this->data;
        $data = $this->repository->getById($id);
        $id = Crypt::encryptString($id);
        $ref["url"] = route("partner.update", $id);
        return view($this->data['view_directory'] . '.form', compact('ref', "data"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $id = Crypt::decryptString($id);
        $data = $request->validate([
            "name" => ['required', 'string', 'max:100'],
            "description" => ['required', 'string'],
            'image' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:5120'], // Mengubah 'image' menjadi 'file'
        ],[
            "name.required" => "Nama mitra harus di isi",
            "description.required" => "Deskripsi mitra harus di isi",
            'image.image' => "Berkas harus berupa gambar",
            'image.mimes' => "Berkas harus dalam format PNG, JPG, atau JPEG",
            'image.max' => "Berkas tidak boleh lebih dari 5MB",
        ]);

       $data['updated_by'] = auth()->user()->id;

        try {
            if (isset($data["image"])) {
                $old_image = $this->repository->getById($id)->image;
                unlink(storage_path().'/app/public/'.$old_image);
                $image_path = $request->file('image')->store('images', 'public');
                $data["image"] =  $image_path;
            } else {
                unset($data["image"]);
            }
            $this->repository->edit($id, $data);
            return redirect()->route('partner.index')->with('success', 'Berhasil mengubah partner ' . $data["name"]);
        } catch (Exception $e) {
            if (env('APP_DEBUG')) {
                return $e->getMessage();
            }
            return back()->with('error', "Oops..!! Terjadi keesalahan saat mengubah data")->withInput($request->input);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $id = Crypt::decryptString($id);
        $image = $this->repository->getById($id);
        $image_path = storage_path().'/app/public/'.$image->image;
        try 
        {
            unlink($image_path);
            $this->repository->destroy($id);
            return redirect()->route('partner.index')->with('success', 'Partner berhasil dihapus');
        }
        catch (\Exception $e)
        {
            if (env('APP_DEBUG')) {
                return $e->getMessage();
            }
            return back()->with('error', 'Terjadi kesalahan saat menghapus partner');
        }
    }
}
