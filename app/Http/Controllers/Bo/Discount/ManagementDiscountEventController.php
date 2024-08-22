<?php

namespace App\Http\Controllers\Bo\Discount;

use App\Repositories\DiscountRepository;
use App\Repositories\DiscountCostumerRepository;
use Exception;
use Helper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Crypt;

class ManagementDiscountEventController extends Controller
{
    private DiscountRepository $repository;
    private DiscountCostumerRepository $d_customer_repository;

    protected $data = array();

    public function __construct(DiscountRepository $repository, DiscountCostumerRepository $d_customer_repository)
    {
        $this->repository = $repository;
        $this->d_customer_repository = $d_customer_repository;
        $this->data['title'] = 'event discounts';
        $this->data['view_directory'] = "admin.feature.discount.event";
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
                ->addColumn('waktu', function ($row) {
                    $nStartTime = Carbon::createFromFormat('H:i:s', $row["start_time"])->format('H:i');
                    $nStartDate = Carbon::createFromFormat('Y-m-d', $row["start_date"])->format('d F Y');
                    $nEndTime = Carbon::createFromFormat('H:i:s', $row["end_time"])->format('H:i');
                    $nEndDate = Carbon::createFromFormat('Y-m-d', $row["end_date"])->format('d F Y');

                    return $nStartDate ." (". $nStartTime .") - ". $nEndDate . " (". $nEndTime .")";
                })
                ->addColumn('status', function ($row) {
                    $hari_ini = Carbon::now()->format('Y-m-d H:i:s');
                    $jenis = '';
                    if($row["start_date"] == $row["end_date"])
                    {
                        $jenis = '<div class="badge bg-danger">Flash Sale</div>';
                    }
                    else
                    {
                        $jenis = '<div class="badge bg-info">Discount Event</div>';
                    } 

                    $status = '';
                    if($hari_ini < ($row['start_date'].' '.$row['start_time']))
                    {
                        $status = '<div class="badge bg-secondary">Belum Aktif</div>';
                    }
                    elseif (($hari_ini >= ($row['start_date'].' '.$row['start_time'])) && ($hari_ini <= ($row['end_date'].' '.$row['end_time'])))
                    {
                        $status = '<div class="badge bg-success">Sedang Aktif</div>';
                    }
                    else
                    {
                        $status = '<div class="badge bg-warning">Tidak Aktif</div>';
                    }
                    return $jenis.' '.$status;
                })
                ->addColumn('action', function ($row) {
                    $row["id"] = Crypt::encryptString($row["id"]);
                    $btn = '<div class="d-flex">
                            <form method="POST" action="' . route('discount.active', $row["id"]) . '">
                                ' . csrf_field() . '
                                ' . ($row["is_active"] == '1' ? '<button class ="btn bg-gradient-success btn-tooltip mx-1"><i class="bi bi-toggle-on"></i></button>' : '<button class ="btn bg-gradient-secondary btn-tooltip mx-1"><i class="bi bi-toggle-off"></i></button>') . '
                            </form>
                            <form method="POST" action="' . route('discount.destroy', $row["id"]) . '">
                                        ' . method_field("DELETE") . '
                                        ' . csrf_field() . '
                                        <a href="' . route("discount.edit", $row["id"]) . '" class="btn bg-gradient-info btn-tooltip"><i class="bi bi-pencil-square"></i></a>
                                        <button type="button" id="deleteRow" data-message="' . $row["name"] . '" class="btn bg-gradient-danger btn-tooltip show-alert-delete-box" data-toggle="tooltip" title="Delete"><i class="bi bi-trash"></i></button>
                            </form></div>';
                    return $btn;
                })
                ->rawColumns(['action', 'waktu', 'status'])
                ->make(true);
        }
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $ref = $this->data;
        $ref["url"] = route("discount.store");
        return view($this->data['view_directory'] . '.form', compact('ref'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            "title" => ['required', 'string', 'max:100'],
            "code_discount" => ['required', 'string', 'max:10', 'unique:discounts,code_discount'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i'],
            "description_discount" => ['required', 'string'],
            'image_banner' => ['required', 'image', 'mimes:png,jpg,jpeg', 'max:5120'],
            "discount_amount" => ['required', 'string', 'max:3'],
            "costumer_id" => ['nullable'],
        ], [], [
            "title" => "Nama discount",
            "code_discount" => "Kode discount",
            "start_time" => "Waktu discount dimulai",
            "end_time" => "Waktu discount berakhir",
            "end_date" => "Tanggal diskon berakhir harus di isi",
            "start_date" => "Tanggal diskon dimulai harus benar",
            "description_discount" => "Deskripsi Diskon",
            'image_banner' => "Banner",
            "discount_amount" => "Besaran diskon",
        ]);
        
        $data['created_by'] = auth()->user()->id;
        $data['category_discount'] = 'EVN';
        $data['code_discount'] = strtoupper($data['code_discount']);
        $data['is_active'] = '1';
        $data['id'] = 'DIS-' . Helper::table_id();

        try
        {
            //costumer id proses
            if (isset($data['costumer_id'])) {
                foreach ($data['costumer_id'] as $key => $value) {
                    $record_user['id'] = 'DCS-' . Helper::table_id();
                    $record_user['costumer_id'] = $value;
                    $record_user['code_discount'] = $data['code_discount'];
                    $record_user['discount_id'] =  $data['id'];
                    $record_user['is_used'] =  '0';
                    $record_user['created_by'] = auth()->user()->id;
                    $record_user['updated_by'] = auth()->user()->id;
                    $this->d_customer_repository->store($record_user);
                }
                unset($data['costumer_id']);
            }

            //image proses
            $image_path = $request->file('image_banner')->store('images', 'public'); 
            //save image
            $data["image_banner"] = $image_path;
            //proses save
            $this->repository->store($data);
            return redirect()->route('discount.index')->with('success', 'Berhasil menambah diskon ' . $data["title"]);
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
        $data = $this->repository->getById($id)->toArray();
        $id = Crypt::encryptString($id);
        $ref["url"] = route("discount.update", $id);
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
            "code_discount" => ['required', 'string', 'max:10', Rule::unique('discounts', 'code_discount')->ignore($id)],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i'],
            "description_discount" => ['required', 'string'],
            'image_banner' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:5120'],
            "discount_amount" => ['required', 'string', 'max:3'],
            "costumer_id" => ['nullable'],
        ], [], [
            "title" => "Nama discount",
            "code_discount" => "Kode discount",
            "start_time" => "Waktu discount dimulai",
            "end_time" => "Waktu discount berakhir",
            "end_date" => "Tanggal diskon berakhir harus di isi",
            "start_date" => "Tanggal diskon dimulai harus benar",
            "description_discount" => "Deskripsi Diskon",
            'image_banner' => "Banner",
            "discount_amount" => "Besaran diskon",
        ]);
        
        $data['updated_by'] = auth()->user()->id;
        $data['code_discount'] = strtoupper($data['code_discount']);

        try {
            //hapus all yang lama
            $this->d_customer_repository->destroy($id);
            //masukin yang baru
            //user id proses
            if (isset($data['costumer_id'])) {
                foreach ($data['costumer_id'] as $key => $value) {
                    $record_user['id'] = 'DCS-' . Helper::table_id();
                    $record_user['costumer_id'] = $value;
                    $record_user['code_discount'] = $data['code_discount'];
                    $record_user['discount_id'] =  $id;
                    $record_user['is_used']    =  '0';
                    $record_user['created_by'] = auth()->user()->id;
                    $record_user['updated_by'] = auth()->user()->id;
                    $this->d_customer_repository->store($record_user);
                }
                unset($data['costumer_id']);
            }

            if (isset($data["image_banner"])) {
                $old_image = $this->repository->getById($id)->image_banner;
                unlink(storage_path().'/app/public/'.$old_image);
                $image_path = $request->file('image_banner')->store('images', 'public');
                $data["image_banner"] =  $image_path;
            } else {
                unset($data["image_banner"]);
            }
            $this->repository->edit($id, $data);
            return redirect()->route('discount.index')->with('success', 'Berhasil mengubah diskon ' . $data["title"]);
        } catch (Exception $e) {
            if (env('APP_DEBUG')) {
                return $e->getMessage();
            }
            return back()->with('error', "Oops..!! Terjadi keesalahan saat mengubah data")->withInput($request->input);
        }
    }

    public function active(string $id)
    {
        $id = Crypt::decryptString($id);
        $discount = $this->repository->getById($id);
        if ($discount->is_active == '1')
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
            return redirect()->route('discount.index')->with('success', 'Berhasil ' . $activasi . ' diskon ' . $discount->title);
        }
        catch (Exception $e)
        {
            if (env('APP_DEBUG'))
            {
                return $e->getMessage();
            }
            return back()->with('error', "Oops..!! Terjadi keesalahan saat " . $activasi . " diskon");
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $id = Crypt::decryptString($id);
        $image = $this->repository->getById($id);
        $image_path = storage_path().'/app/public/'.$image->image_banner;
        try 
        {
            unlink($image_path);
            $this->d_customer_repository->destroy($id);
            $this->repository->destroy($id);
            return redirect()->route('discount.index')->with('success', 'Diskon berhasil dihapus');
        }
        catch (\Exception $e)
        {
            if (env('APP_DEBUG')) {
                return $e->getMessage();
            }
            return back()->with('error', 'Terjadi kesalahan saat menghapus diskon');
        }
    }
}
