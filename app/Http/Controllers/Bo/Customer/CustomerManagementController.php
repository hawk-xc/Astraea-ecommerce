<?php

namespace App\Http\Controllers\Bo\Customer;

use App\Repositories\CustomerRepository;
use Exception;
use Helper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;

class CustomerManagementController extends Controller
{
    private CustomerRepository $repository;
    protected $data = array();

    public function __construct(CustomerRepository $repository)
    {
        $this->repository = $repository;
        $this->data['title'] = 'Management Customer';
        $this->data['view_directory'] = "admin.feature.customer";
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
                            <form method="POST" action="' . route('management_customer.active', $row["id"]) . '">
                                ' . csrf_field() . '
                                ' . ($row["is_active"] == '1' ? '<button class ="btn bg-gradient-success btn-tooltip mx-1"><i class="bi bi-toggle-on"></i></button>' : '<button class ="btn bg-gradient-secondary btn-tooltip mx-1"><i class="bi bi-toggle-off"></i></button>') . '
                            </form>
                            </div>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function sDatas(Request $request) {
        $searchTerm = $request->input('q');
        $filteredProducts = $this->repository->getSearch($searchTerm);
        $formattedData = $filteredProducts->map(function($costumer) {
            return [
                'id' => $costumer->id,
                'text' => $costumer->name . " - " . $costumer->username  
            ];
        });
        return response()->json(['results' => $formattedData]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
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
        //
    }

    public function active(string $id)
    {
        $id = Crypt::decryptString($id);
        $customer = $this->repository->getById($id);
        if ($customer->is_active == '1')
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
            return redirect()->route('management_customer.index')->with('success', 'Berhasil ' . $activasi . ' Customer ' . $customer->username);
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
        //
    }
}
