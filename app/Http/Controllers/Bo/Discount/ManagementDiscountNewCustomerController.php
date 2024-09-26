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
use Illuminate\Support\Facades\File;

class ManagementDiscountNewCustomerController extends Controller
{
    private DiscountRepository $repository;
    private DiscountCostumerRepository $d_customer_repository;

    protected $data = array();

    public function __construct(DiscountRepository $repository, DiscountCostumerRepository $d_customer_repository)
    {
        $this->repository = $repository;
        $this->d_customer_repository = $d_customer_repository;
        $this->data['title'] = 'new customer discount';
        $this->data['view_directory'] = "admin.feature.discount.newCustomer";
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ref = $this->data;
        $data = $this->repository->getDiscNewCostumer()->toArray();
        $data["id"] = Crypt::encryptString($data["id"]);
        $ref["url"] = route("disc_new_customer.update", $data['id']);
        return view($this->data['view_directory'] . '.form', compact('ref', 'data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

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
    public function edit(string $id) {}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $id = Crypt::decryptString($id);
        $data = $request->validate([
            "title" => ['required', 'string', 'max:100'],
            "code_discount" => ['required', 'string', 'max:10', Rule::unique('discounts', 'code_discount')->ignore($id)],
            "description_discount" => ['required', 'string'],
            'image_banner' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:5120'],
            "discount_amount" => ['required', 'string', 'max:3'],
        ], [], [
            "title" => "Nama discount",
            "code_discount" => "Kode discount",
            "description_discount" => "Deskripsi Diskon",
            'image_banner' => "Banner",
            "discount_amount" => "Besaran diskon",
        ]);

        $data['updated_by'] = auth()->user()->id;
        $old_image = $this->repository->getById($id)->image_banner;
        $data['code_discount'] = strtoupper($data['code_discount']);

        try {

            if (isset($data["image_banner"])) {
                if ($old_image != null) {
                    if (File::exists(asset($old_image))) {
                        unlink(storage_path() . '/app/public/' . $old_image);
                    }
                }
                $image_path = $request->file('image_banner')->store('images', 'public');
                $data["image_banner"] =  $image_path;
            } else {
                unset($data["image_banner"]);
            }
            $this->d_customer_repository->edit_new($id, ['code_discount' => $data['code_discount']]);
            $this->repository->edit($id, $data);
            return redirect()->route('disc_new_customer.index')->with('success', 'Berhasil mengubah diskon ' . $data["title"]);
        } catch (Exception $e) {
            if (env('APP_DEBUG')) {
                return $e->getMessage();
            }
            return back()->with('error', "Oops..!! Terjadi keesalahan saat mengubah data")->withInput($request->input);
        }
    }

    public function active(string $id) {}
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) {}
}
