<?php

namespace App\Http\Controllers\Bo\Account;

use App\Repositories\ProfilesRepository;
use App\Repositories\RolesRepository;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;

class ProfileController extends Controller
{
    private ProfilesRepository $repository;
    private RolesRepository $roles;
    protected $data = array();

    public function __construct(ProfilesRepository $repository, RolesRepository $roles)
    {
        $this->repository = $repository;
        $this->roles = $roles;
        $this->data['title'] = 'profil';
        $this->data['view_directory'] = "admin.feature.profile";
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = $this->roles->getAll();
        $ref = $this->data;
        $id = Crypt::encryptString(auth()->user()->id);
        $ref["url"] = route("profile.update", $id);
        $data = auth()->user();
        return view($this->data['view_directory'] . '.index', compact('ref', 'data', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $id = Crypt::decryptString($id);
        $data = $request->validate([
            "name" => ['required', 'string', 'max:50'],
            "username" => ['required', 'string', 'unique:users,username,' . $id, 'max:50'],
            "address" => ['required', 'string'],
            "password" => ['nullable', 'string', 'min:6', 'max:50']
        ], [], [
            "name" => "Nama Asli",
            "username" => "Nama pengguna",
            "address" => "Alamat",
            "password" => "Kata kunci",
        ]);
        
        if (is_null($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = bcrypt($data["password"]);
        }
        $data['updated_by'] = auth()->user()->id;

        try {
            $this->repository->edit($id, $data);
            return redirect()->route('profile.index')->with('success', 'Berhasi mengubah pengguna ' . $data["name"]);
        } catch (Exception $e) {
            if (env('APP_DEBUG')) {
                return $e->getMessage();
            }
            return back()->with('error', "Oops..!! Terjadi keesalahan saat mengubah data")->withInput($request->input);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
