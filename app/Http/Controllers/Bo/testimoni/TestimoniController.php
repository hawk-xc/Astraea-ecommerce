<?php

namespace App\Http\Controllers\Bo\testimoni;

use App\Http\Controllers\Controller;
use App\Repositories\TestimoniRepository;
use Carbon\Carbon;
use Exception;
use Helper;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Crypt;

class TestimoniController extends Controller
{
    private TestimoniRepository $repository;
    protected $data = array();

    public function __construct(TestimoniRepository $repository)
    {
        $this->repository = $repository;
        $this->data['title'] = 'Testimoni';
        $this->data['view_directory'] = "admin.feature.testimoni";
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // dd($this->repository->getAll());
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
                ->editColumn(
                    "rating",
                    function ($inquiry) {
                        return '<p class="star-ratingt"><span class="star full">&#9733;</span> ' . $inquiry["rating"] . '</p>';
                    }
                )
                ->addColumn('action', function ($row) {
                         $row["customer_id"] = Crypt::encryptString($row["customer_id"]);
                        $btn = '<form method="POST" action="' . route('testimoni.destroy', $row["customer_id"]) . '">
                                        ' . method_field("DELETE") . '
                                        ' . csrf_field() .'<a href="mailto:'.$row['customerData']['email'].'" class="btn bg-gradient-info btn-tooltip">
                                    <i class="bi bi-envelope-at"></i>
                                </a>
                                <button type="button" id="deleteRow" data-message="' . $row["customerData"]["name"] . '" class="btn bg-gradient-danger btn-tooltip show-alert-delete-box" data-toggle="tooltip" title="Delete"><i class="bi bi-trash"></i></button>
                                </form>';
                    return $btn;
                })
                ->rawColumns(['action', 'rating'])
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
        $id = Crypt::decryptString($id);
        $record['is_delete'] = '1';
        try {
            // Menyimpan data ke database
            $this->repository->edit($id, $record);
            return redirect()->back()->with('success', 'Testimoni berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
