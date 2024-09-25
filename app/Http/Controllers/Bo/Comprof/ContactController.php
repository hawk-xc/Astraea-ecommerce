<?php

namespace App\Http\Controllers\Bo\Comprof;

use App\Repositories\ContactUsRepository;
use Exception;
use Helper;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;

class ContactController extends Controller
{
    private ContactUsRepository $repository;
    protected $data = array();

    public function __construct(ContactUsRepository $repository)
    {
        $this->repository = $repository;
        $this->data['title'] = 'Contact Us';
        $this->data['view_directory'] = "admin.feature.comprof.contact";
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ref = $this->data;
        $id = Crypt::encryptString(1);
        $data = $this->repository->getById('1');
        $ref["url"] = route("contact.update", $id);
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
        $id = Crypt::decryptString($id);
        $data = $request->validate([
            "phone_number" => ['required', 'string', 'max:14'],
            "whatsapp" => ['required', 'string', 'max:14'],
            "email" => ['required', 'email', 'string', 'max:50'],
            "address" => ['required', 'string', 'max:255'],
            "maps" => ['required', 'string', 'max:500'],
            'id_distric' =>  ['required', 'string']
        ], [], [
            "phone_number" => "Nomor Telepon",
            "whatsapp" => "Nomor whatsapp",
            "email" => "email",
            "address" => "alamat",
            "maps" => "almat google maps",
            "id_distric" => "Kabupaten / Kota"
        ]);
        $data['updated_by'] = auth()->user()->id;
        try {
            $this->repository->edit($id, $data);
            return redirect()->route('contact.index')->with('success', 'Berhasil memperbarui Contact');
        } catch (Exception $e) {
            if (env('APP_DEBUG')) {
                return $e->getMessage();
            }
            return back()->with('error', "Oops..!! Terjadi keesalahan saat menyimpan data")->withInput($request->input);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
