<?php

namespace App\Http\Controllers\Bo\Comprof;

use App\Repositories\EventRepository;
use Exception;
use Helper;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;

class EventController extends Controller
{
    private EventRepository $repository;
    protected $data = array();

    public function __construct(EventRepository $repository)
    {
        $this->repository = $repository;
        $this->data['title'] = 'Event';
        $this->data['view_directory'] = "admin.feature.comprof.event";
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
                    $btn = '<div class="d-flex">
                            <form method="POST" action="' . route('events.active', $row["id"]) . '">
                                ' . csrf_field() . '
                                ' . ($row["is_active"] == '1' ? '<button class ="btn bg-gradient-success btn-tooltip mx-1"><i class="bi bi-toggle-on"></i></button>' : '<button class ="btn bg-gradient-secondary btn-tooltip mx-1"><i class="bi bi-toggle-off"></i></button>') . '
                            </form>
                            <form method="POST" action="' . route('event.destroy', $row["id"]) . '">
                                        ' . method_field("DELETE") . '
                                        ' . csrf_field() . '
                                        <a href="' . route("event.edit", $row["id"]) . '" class="btn bg-gradient-info btn-tooltip"><i class="bi bi-pencil-square"></i></a>
                                        <button type="button" id="deleteRow" data-message="' . $row["name"] . '" class="btn bg-gradient-danger btn-tooltip show-alert-delete-box" data-toggle="tooltip" title="Delete"><i class="bi bi-trash"></i></button>
                            </form></div>';
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
        $ref["url"] = route("event.store");
        return view($this->data['view_directory'] . '.form', compact('ref'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            "title" => ['required', 'string', 'max:100'],
            "description" => ['required', 'string'],
            'cover_image' => ['required', 'image', 'mimes:png,jpg,jpeg', 'max:5120'],
            'tanggal_acara' => ['required', 'date'],
        ],[
            "title.required" => "Nama event harus di isi",
            "tanggal_acara.required" => "Tanggal event harus di isi",
            "tanggal_acara.date" => "Tanggal event harus benar",
            "description.required" => "Deskripsi event harus di isi",
            'cover_image.required' => "Cover harus diisi",
            'cover_image.image' => "Berkas harus berupa gambar",
            'cover_image.mimes' => "Berkas harus dalam format PNG, JPG, atau JPEG",
            'cover_image.max' => "Berkas tidak boleh lebih dari 5MB",
        ]);

        $data['id'] = 'EVN-' . Helper::table_id();
        $data['created_by'] = auth()->user()->id;
        $data['is_active'] = '1';
        $data['slug'] = $this->repository->sluggable($data['title']);

        try
        {
            $image_path = $request->file('cover_image')->store('images', 'public'); 
            //save image
            $data["cover_image"] = $image_path;
            //proses save
            $this->repository->store($data);
            return redirect()->route('event.index')->with('success', 'Berhasil menambah event ' . $data["title"]);
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
        $ref["url"] = route("event.update", $id);
        return view($this->data['view_directory'] . '.form', compact('ref', "data"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $id = Crypt::decryptString($id);
        $data = $request->validate([
            "title" => ['required', 'string', 'max:100'],
            "description" => ['required', 'string'],
            'cover_image' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:5120'],
            'tanggal_acara' => ['required', 'date'],
        ],[
            "title.required" => "Nama event harus di isi",
            "tanggal_acara.required" => "Tanggal event harus di isi",
            "tanggal_acara.date" => "Tanggal event harus benar",
            "description.required" => "Deskripsi event harus di isi",
            'cover_image.image' => "Berkas harus berupa gambar",
            'cover_image.mimes' => "Berkas harus dalam format PNG, JPG, atau JPEG",
            'cover_image.max' => "Berkas tidak boleh lebih dari 5MB",
        ]);

        $data['updated_by'] = auth()->user()->id;
        $data['slug'] = $this->repository->sluggable($data['title']);

        try {
            if (isset($data["cover_image"])) {
                $old_image = $this->repository->getById($id)->cover_image;
                unlink(storage_path().'/app/public/'.$old_image);
                $image_path = $request->file('cover_image')->store('images', 'public');
                $data["cover_image"] =  $image_path;
            } else {
                unset($data["cover_image"]);
            }
            $this->repository->edit($id, $data);
            return redirect()->route('event.index')->with('success', 'Berhasil mengubah event ' . $data["title"]);
        } catch (Exception $e) {
            if (env('APP_DEBUG')) {
                return $e->getMessage();
            }
            return back()->with('error', "Oops..!! Terjadi keesalahan saat mengubah data")->withInput($request->input);
        }
    }

    /*
    * active event
    */
    public function active(string $id)
    {
        $id = Crypt::decryptString($id);
        $event = $this->repository->getById($id);
        if ($event->is_active == '1')
        {
            $record['is_active'] = '0';
            $activasi = 'menonaktifkan';
        } 
        else
        {
            $record['is_active'] = '1';
            $activasi = 'mengaktifkan';
        }
        
        $record['updated_by'] = auth()->user()->id;

        try 
        {
            $this->repository->edit($id, $record);
            return redirect()->route('event.index')->with('success', 'Berhasil ' . $activasi . ' Event ' . $event->title);
        }
        catch (Exception $e)
        {
            if (env('APP_DEBUG'))
            {
                return $e->getMessage();
            }
            return back()->with('error', "Oops..!! Terjadi keesalahan saat " . $activasi . " event");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $id = Crypt::decryptString($id);
        $image = $this->repository->getById($id);
        $image_path = storage_path().'/app/public/'.$image->cover_image;
        try 
        {
            unlink($image_path);
            $this->repository->destroy($id);
            return redirect()->route('event.index')->with('success', 'Event berhasil dihapus');
        }
        catch (\Exception $e)
        {
            if (env('APP_DEBUG')) {
                return $e->getMessage();
            }
            return back()->with('error', 'Terjadi kesalahan saat menghapus event');
        }
    }
}
