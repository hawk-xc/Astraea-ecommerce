<?php

namespace App\Http\Controllers\Bo\visitor;

use App\Http\Controllers\Controller;
use App\Repositories\VisitorMailRepository;
use Carbon\Carbon;
use Exception;
use Helper;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Crypt;

class VisitorMailController extends Controller
{
    private VisitorMailRepository $repository;
    protected $data = array();

    public function __construct(VisitorMailRepository $repository)
    {
        $this->repository = $repository;
        $this->data['title'] = 'Visitor Mail';
        $this->data['view_directory'] = "admin.feature.mail_visitor";
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
                        $btn = '
                                <a href="mailto:' . $row["email"] . '" class="btn bg-gradient-info btn-tooltip"><i class="bi bi-envelope-at"></i></a>
                                ';
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
