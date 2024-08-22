<?php

namespace App\Http\Controllers\Fo\customer;

use App\Repositories\ContactUsRepository;
use App\Repositories\AboutUsRepository;
use App\Repositories\DistrictRepository;
use App\Repositories\CustomerRepository;
use App\Repositories\UserOrderRepository;
use App\Repositories\TestimoniRepository;

use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyEmail;
use Illuminate\Support\Facades\Auth;

use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Foundation\Http\FormRequest;
use App\Http\Controllers\Controller;

class DashboardCustomerController extends Controller
{
    private ContactUsRepository $contactUsRepository;
    private AboutUsRepository $aboutUsRepository;
    private DistrictRepository $districtRepository;
    private UserOrderRepository $userOrderRepository;
    private TestimoniRepository $testimoniRepository;

    private CustomerRepository $repository;
    protected $data = array();

    public function __construct(CustomerRepository $repository, ContactUsRepository $contactUsRepository, AboutUsRepository $aboutUsRepository, DistrictRepository $districtRepository, UserOrderRepository $userOrderRepository, TestimoniRepository $testimoniRepository)
    {
        $this->repository = $repository;
        $this->contactUsRepository = $contactUsRepository;
        $this->aboutUsRepository = $aboutUsRepository;
        $this->districtRepository = $districtRepository;
        $this->userOrderRepository = $userOrderRepository;
        $this->testimoniRepository = $testimoniRepository;
        $this->data['title'] = 'Profile';
        $this->data['view_directory'] = "guest.feature.customer";
    }

    public function index()
    {
        $ref = $this->data;
        $s = auth()->guard('customer')->user()['id'];
        $data['orders'] = $this->userOrderRepository->getAll($s);
        $data['orderspa'] = $data['orders']->links();
        $data['orders'] = $data['orders']->map(function ($pesanan) {
                    $pesanan->id = Crypt::encrypt($pesanan->id);
                    $pesanan->no_nota = Crypt::encrypt($pesanan->no_nota);
                    return $pesanan;
                });
        $data['contact'] = $this->contactUsRepository->getById('1');
        $data['about'] = $this->aboutUsRepository->getById('1');
        $data['district'] =  $this->districtRepository->getById(auth()->guard('customer')->user()['district_id']);
        $data['testimoni'] = $this->testimoniRepository->getById($s);
        $data['s'] = Crypt::encryptString($s);
        return view($this->data['view_directory'] . '.index', compact('ref', 'data'));
    }

    public function edit()
    {
        $ref = $this->data;
        $ref['title'] = 'Edit Profile';
        $data['contact'] = $this->contactUsRepository->getById('1');
        $data['about'] = $this->aboutUsRepository->getById('1');
        return view($this->data['view_directory'] . '.form', compact('ref', 'data'));
    }

    public function update(Request $request)
    {

        $id = auth()->guard('customer')->user()->id;
        $data = $request->validate([
                "name" => ['required', 'string', 'max:100'],
                "username" => ['required', 'string', Rule::unique('customers')->ignore($id)],
                "email" => ['required', 'email', Rule::unique('customers')->ignore($id)],
                "phone" => ['required', 'string'],
                "wa" => ['required', 'string'],
                "tgl_lahir" => ['required', 'string'],
                "jenis_kelamin" => ['required', 'string'],
                "district_id" => ['required'],
                "address" => ['required', 'string'],
            ], [], [
                "name" => "Nama",
                "username" => "Nama pengguna",
                "email" => "Email ",
                "phone" => "No Telepon",
                "wa" => "No Whatsapp",
                "tgl_lahir" => "Tanggal Lahir",
                "jenis_kelamin" => "Jenis Kelamin",
                "district_id" => "Kabupaten / Kota",
                "address" => "Alamat",
            ]);
        
        try
        {
            $data['updated_by']     = $id;

            if(auth()->guard('customer')->user()->email != $data['email'])
            {
                $token = sha1($data['email'] . now());
                Mail::to($data['email'])->send(new VerifyEmail($token));
                $data['verification_token']     = $token;
                $this->repository->edit($id, $data);
                Auth::guard('customer')->logout();
                return redirect()->route('loginf.customer')->with('toast_success', "Berhasil Mengupdate Profile");
            }

            $this->repository->edit($id, $data);
            return redirect()->route('dashboard.customer')->with('toast_success', "Berhasil Mengupdate Profile");
        }
        catch (Exception $e)
        {
            if (env('APP_DEBUG'))
            {
                return $e->getMessage();
            }
            return back()->with('toast_warning', "Oops..!! Terjadi keesalahan saat menyimpan data")->withInput($request->input);
        }

    }

    public function upassword(Request $request)
    {
        $id = auth()->guard('customer')->user()->id;
        $data = $request->validate([
                "password" => ['required', 'string', 'min:8', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/'],
                "password_confirmation" => ['required', 'same:password'],                
            ], [], [
                "password" => "Password",
                "password_confirmation" => "Konfirmasi Password",
            ]);
        
        try
        {
            $data['updated_by']     = $id;
            unset($data['password_confirmation']);
            $data['password']       = Hash::make($data['password']);
            $this->repository->edit($id, $data);
            return redirect()->route('dashboard.customer')->with('success', "Berhasil Mengupdate Profile");
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

}
