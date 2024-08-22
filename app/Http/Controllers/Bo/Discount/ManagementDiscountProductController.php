<?php

namespace App\Http\Controllers\Bo\Discount;

use App\Repositories\DiscountRepository;
use App\Repositories\DiscountProductRepository;
use App\Repositories\DiscountHampersRepository;
use App\Repositories\DiscountUserRepository;
use App\Repositories\DiscountCostumerRepository;
use Exception;
use Helper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;

class ManagementDiscountController extends Controller
{
    private DiscountRepository $repository;
    private DiscountProductRepository $d_product_repository;
    private DiscountHampersRepository $d_hampers_repository;
    private DiscountUserRepository $d_user_repository;
    private DiscountCostumerRepository $d_customer_repository;

    protected $data = array();

    public function __construct(DiscountRepository $repository, DiscountProductRepository $d_product_repository, DiscountHampersRepository $d_hampers_repository, DiscountUserRepository $d_user_repository, DiscountCostumerRepository $d_customer_repository)
    {
        $this->repository = $repository;
        $this->d_product_repository = $d_product_repository;
        $this->d_hampers_repository = $d_hampers_repository;
        $this->d_user_repository = $d_user_repository;
        $this->d_customer_repository = $d_customer_repository;
        $this->data['title'] = 'Management Discount';
        $this->data['view_directory'] = "admin.feature.discount";
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
            "code_discount" => ['required', 'string', 'max:10'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i'],
            "description_discount" => ['required', 'string'],
            'image_banner' => ['required', 'image', 'mimes:png,jpg,jpeg', 'max:5120'],
            "discount_amount" => ['required', 'string', 'max:3'],
            "user_id" => ['nullable'],
            "product_id" => ['nullable'],
            "hampers_id" => ['nullable'],
        ],[
            "title.required" => "Nama discount harus di isi",
            "code_discount.required" => "Kode discount harus di isi",
            "start_time.required" => "Waktu discount dimulai harus di isi",
            "end_time.required" => "Waktu discount berakhir harus di isi",
            "end_date.required" => "Tanggal diskon dimulai harus di isi",
            "tanggal_acara.required" => "Tanggal diskon berakhir harus di isi",
            "end_date.date" => "Tanggal diskon dimulai harus benar",
            "tanggal_acara.date" => "Tanggal diskon berakhir harus benar",
            "description_discount.required" => "Deskripsi Diskon harus di isi",
            'image_banner.required' => "Banner harus diisi",
            'image_banner.image' => "Berkas harus berupa gambar",
            'image_banner.mimes' => "Berkas harus dalam format PNG, JPG, atau JPEG",
            'image_banner.max' => "Berkas tidak boleh lebih dari 5MB",
            "discount_amount" => "Besaran diskon harus di isi",
        ]);
        
        $data['created_by'] = auth()->user()->id;
        $data['is_active'] = '1';
        $data['id'] = 'DIS-' . Helper::table_id();

        try
        {
            //user id proses
            if (isset($data['user_id'])) {
                foreach ($data['user_id'] as $key => $value) {
                    $record_user['id'] = 'DCU-' . Helper::table_id();
                    $record_user['user_id'] = $value;
                    $record_user['discount_id'] =  $data['id'];
                    $record_user['created_by'] = auth()->user()->id;
                    $record_user['updated_by'] = auth()->user()->id;
                    $this->d_user_repository->store($record_user);
                }
                unset($data['user_id']);
            }

            //product id proses
            if (isset($data['product_id'])) {
                foreach ($data['product_id'] as $key => $value) {
                    $record_product['id'] = 'DCU-' . Helper::table_id();
                    $record_product['product_id'] = $value;
                    $record_product['discount_id'] =  $data['id'];
                    $record_product['created_by'] = auth()->user()->id;
                    $record_product['updated_by'] = auth()->user()->id;
                    $this->d_product_repository->store($record_product);
                }
                unset($data['product_id']);
            }

            //hampers id proses
            if (isset($data['hampers_id'])) {
                foreach ($data['hampers_id'] as $key => $value) {
                    $record_hampres['id'] = 'DCU-' . Helper::table_id();
                    $record_hampres['hampers_id'] = $value;
                    $record_hampres['discount_id'] =  $data['id'];
                    $record_hampres['created_by'] = auth()->user()->id;
                    $record_hampres['updated_by'] = auth()->user()->id;
                    $this->d_hampers_repository->store($record_hampres);
                }
                unset($data['hampers_id']);
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
        $ref = $this->data;
        $data = $this->repository->getById($id)->toArray();
        $ref["url"] = route("discount.update", $id);
        return view($this->data['view_directory'] . '.form', compact('ref', "data"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            "title" => ['required', 'string', 'max:100'],
            "code_discount" => ['required', 'string', 'max:10'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i'],
            "description_discount" => ['required', 'string'],
            'image_banner' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:5120'],
            "discount_amount" => ['required', 'string', 'max:3'],
            "costumer_id" => ['nullable'],
            "product_id" => ['nullable'],
            "hampers_id" => ['nullable'],
        ],[
            "title.required" => "Nama discount harus di isi",
            "code_discount.required" => "Kode discount harus di isi",
            "start_time.required" => "Waktu discount dimulai harus di isi",
            "end_time.required" => "Waktu discount berakhir harus di isi",
            "end_date.required" => "Tanggal diskon dimulai harus di isi",
            "tanggal_acara.required" => "Tanggal diskon berakhir harus di isi",
            "end_date.date" => "Tanggal diskon dimulai harus benar",
            "tanggal_acara.date" => "Tanggal diskon berakhir harus benar",
            "description_discount.required" => "Deskripsi Diskon harus di isi",
            'image_banner.required' => "Banner harus diisi",
            'image_banner.image' => "Berkas harus berupa gambar",
            'image_banner.mimes' => "Berkas harus dalam format PNG, JPG, atau JPEG",
            'image_banner.max' => "Berkas tidak boleh lebih dari 5MB",
            "discount_amount" => "Besaran diskon harus di isi",
        ]);
        
        $data['updated_by'] = auth()->user()->id;

        try {
            //hapus all yang lama
            $this->d_hampers_repository->destroy($id);
            $this->d_product_repository->destroy($id);
            $this->d_customer_repository->destroy($id);
            //masukin yang baru
            //user id proses
            if (isset($data['costumer_id'])) {
                foreach ($data['costumer_id'] as $key => $value) {
                    $record_user['id'] = 'DCS-' . Helper::table_id();
                    $record_user['costumer_id'] = $value;
                    $record_user['discount_id'] =  $id;
                    $record_user['created_by'] = auth()->user()->id;
                    $record_user['updated_by'] = auth()->user()->id;
                    $this->d_customer_repository->store($record_user);
                }
                unset($data['costumer_id']);
            }

            //product id proses
            if (isset($data['product_id'])) {
                foreach ($data['product_id'] as $key => $value) {
                    $record_product['id'] = 'DCU-' . Helper::table_id();
                    $record_product['product_id'] = $value;
                    $record_product['discount_id'] =  $id;
                    $record_product['created_by'] = auth()->user()->id;
                    $record_product['updated_by'] = auth()->user()->id;
                    $this->d_product_repository->store($record_product);
                }
                unset($data['product_id']);
            }

            //hampers id proses
            if (isset($data['hampers_id'])) {
                foreach ($data['hampers_id'] as $key => $value) {
                    $record_hampres['id'] = 'DCU-' . Helper::table_id();
                    $record_hampres['hampers_id'] = $value;
                    $record_hampres['discount_id'] =  $id;
                    $record_hampres['created_by'] = auth()->user()->id;
                    $record_hampres['updated_by'] = auth()->user()->id;
                    $this->d_hampers_repository->store($record_hampres);
                }
                unset($data['hampers_id']);
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
        $image = $this->repository->getById($id);
        $image_path = storage_path().'/app/public/'.$image->image_banner;
        try 
        {
            unlink($image_path);
            $this->d_hampers_repository->destroy($id);
            $this->d_product_repository->destroy($id);
            $this->d_user_repository->destroy($id);
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
