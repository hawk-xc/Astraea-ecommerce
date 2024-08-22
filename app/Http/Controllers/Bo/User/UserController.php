<?php

namespace App\Http\Controllers\Bo\User;

use App\Http\Controllers\Controller;
use App\Repositories\RolesRepository;
use App\Repositories\UsersRepository;
use Carbon\Carbon;
use Exception;
use Helper;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Crypt;

class UserController extends Controller
{
    
    private UsersRepository $repository;
    private RolesRepository $role;
    protected $data = array();

    public function __construct(UsersRepository $repository, RolesRepository $role)
    {
        $this->repository = $repository;
        $this->role = $role;
        $this->data['title'] = 'pengguna';
        $this->data['view_directory'] = "admin.feature.users";
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
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
                    $btn = '<form method="POST" action="' . route('user.destroy', $row["id"]) . '">
                                        ' . method_field("DELETE") . '
                                        ' . csrf_field() . '
                                        <a href="' . route("user.edit", $row["id"]) . '" class="btn bg-gradient-info btn-tooltip"><i class="bi bi-pencil-square"></i></a>
                                        <button type="button" id="deleteRow" data-message="' . $row["name"] . '" class="btn bg-gradient-danger btn-tooltip show-alert-delete-box" data-toggle="tooltip" title="Delete"><i class="bi bi-trash"></i></button>
                                    </form>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function sDatas(Request $request) {
        $searchTerm = $request->input('q');
        $filteredProducts = $this->repository->getSearch($searchTerm);
        $formattedData = $filteredProducts->map(function($user) {
            return [
                'id' => $user->id,
                'text' => $user->name . " - " . $user->username  
            ];
        });
        return response()->json(['results' => $formattedData]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = $this->role->getAll();
        $ref = $this->data;
        $ref["url"] = route("user.store");
        return view($this->data['view_directory'] . '.form', compact('ref', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            "role_id" => ['required', 'string', 'max:50'],
            "name" => ['required', 'string', 'max:50'],
            "username" => ['required', 'string', 'unique:users', 'max:50'],
            "email" => ['required', 'email', 'unique:users', 'max:50'],
            "password" => ['required', 'string', 'min:6', 'max:50'],
            "address" => ['required', 'string']
        ], [], [
            "role_id" => "Jenis Pengguna",
            "name" => "Nama Asli",
            "username" => "Nama pengguna",
            "email" => "Email",
            "password" => "Kata kunci",
            "address" => "Alamat",
        ]);
        $data['id'] = 'USR-' . Helper::table_id();
        $data['password'] = bcrypt($data["password"]);
        $data['email_verified_at'] = Carbon::now()->toDateTimeString();
        $data['created_by'] = auth()->user()->id;

        try {
            $this->repository->store($data);
            return redirect()->route('user.index')->with('success', 'Berhasi menambah pengguna ' . $data["name"]);
        } catch (Exception $e) {
            if (env('APP_DEBUG')) {
                return $e->getMessage();
            }
            return back()->with('error', "Oops..!! Terjadi keesalahan saat menyimpan data")->withInput($request->input);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $id = Crypt::decryptString($id);
        $roles = $this->role->getAll();
        $data = $this->repository->getById($id);
        $ref = $this->data;
        $id = Crypt::encryptString($id);
        $ref["url"] = route("user.update", $id);
        // dd($data);
        return view($this->data['view_directory'] . '.form', compact('ref', 'roles', 'data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $id = Crypt::decryptString($id);
        $data = $request->validate([
            "role_id" => ['required', 'string', 'max:50'],
            "name" => ['required', 'string', 'max:50'],
            "username" => ['required', 'string', 'unique:users,username,' . $id, 'max:50'],
            "email" => ['required', 'email', 'unique:users,email,' . $id, 'max:50'],
            "address" => ['required', 'string'],
            "password" => ['nullable', 'string', 'min:6', 'max:50']
        ], [], [
            "role_id" => "Jenis Pengguna",
            "name" => "Nama Asli",
            "username" => "Nama pengguna",
            "email" => "Email",
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
            return redirect()->route('user.index')->with('success', 'Berhasi mengubah pengguna ' . $data["name"]);
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $id = Crypt::decryptString($id);
        try {
            $this->repository->destroy($id);
            return back()->with('success', 'Data berhasil di hapus');
        } catch (Exception $e) {
            if (env('APP_DEBUG')) {
                return $e->getMessage();
            }
            return back()->with('error', "Oops..!! Terjadi keesalahan saat menghapus data");
        }
    }
}
