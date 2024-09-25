<?php

namespace App\Http\Controllers\Bo\AppFee;

use App\Repositories\AppFeeRepository;
use Exception;
use Helper;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AppFeeController extends Controller
{
    private AppFeeRepository $repository;
    protected $data = array();

    public function __construct(AppFeeRepository $repository)
    {
        $this->repository = $repository;
        $this->data['title'] = 'App Fee';
        $this->data['view_directory'] = "admin.feature.appfee";
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ref = $this->data;
        $id = Crypt::encryptString(1);
        $ref["url"] = route("appfee.update", $id);
        $data = $this->repository->getById(1);
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
            "fee_amount" => ['required', 'numeric'],
        ], [], [
            "fee_amount" => "Biaya Aplikasi",
        ]);
        $data['updated_by'] = auth()->user()->id;
        try {
            $this->repository->edit($id, $data);
            return redirect()->route('appfee.index')->with('success', 'Berhasi mengubah Fee App ');
        } catch (Exception $e) {
            if (env('APP_DEBUG')) {
                return $e->getMessage();
            }
            return back()->with('error', "Oops..!! Terjadi keesalahan saat menyimpan data")->withInput($request->input);
        }
        dd($request);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
