<?php

namespace App\Http\Controllers\Fo\auth;

use App\Repositories\ContactUsRepository;
use App\Repositories\AboutUsRepository;
use App\Repositories\CustomerRepository;
use App\Repositories\OrderRepository;
use App\Repositories\OrderHampersRepository;

use App\Repositories\DiscountRepository;
use App\Repositories\DiscountCostumerRepository;

use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyEmail;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Http\FormRequest;


use Helper;

class AuthCustomerController extends Controller
{
    private ContactUsRepository $contactUsRepository;
    private AboutUsRepository $aboutUsRepository;
    private CustomerRepository $repository;

    private OrderRepository $orderProductRepository;
    private OrderHampersRepository $orderHampersRepository;

    private DiscountRepository $discountRepository;
    private DiscountCostumerRepository $d_customer_repository;

    protected $data = array();
    public function __construct(CustomerRepository $repository, ContactUsRepository $contactUsRepository, AboutUsRepository $aboutUsRepository, OrderRepository $orderProductRepository, OrderHampersRepository $orderHampersRepository, DiscountRepository $discountRepository, DiscountCostumerRepository $d_customer_repository)
    {
        $this->contactUsRepository = $contactUsRepository;
        $this->aboutUsRepository = $aboutUsRepository;
        $this->repository = $repository;
        $this->orderProductRepository = $orderProductRepository;
        $this->orderHampersRepository = $orderHampersRepository;
        $this->discountRepository = $discountRepository;
        $this->d_customer_repository = $d_customer_repository;
        $this->data['title'] = 'Login Register';
        $this->data['view_directory'] = "guest.feature.auth";
    }

    public function index()
    {
        if(Auth::guard('customer')->check())
        {
            return redirect()->route('dashboard.customer');
        }

        $ref = $this->data;
        $ref['title'] = 'Login';
        $data['contact'] = $this->contactUsRepository->getById('1');
        $data['about'] = $this->aboutUsRepository->getById('1');
        return view($this->data['view_directory'] . '.index', compact('ref', 'data'));
    }

    public function registerf()
    {
        if(Auth::guard('customer')->check())
        {
            return redirect()->route('dashboard.customer');
        }

        $ref = $this->data;
        $ref['title'] = 'Register';
        $data['contact'] = $this->contactUsRepository->getById('1');
        $data['about'] = $this->aboutUsRepository->getById('1');
        return view($this->data['view_directory'] . '.index_reg', compact('ref', 'data'));
    }

    public function login(Request $request)
    {
        if(Auth::guard('customer')->check())
        {
            return redirect()->route('dashboard.customer');
        }

        $credentials = $request->validate([
            'email' => ['required'],
            'password' => ['required'],
        ]);


        if(null !== $this->repository->getChecklogin($credentials['email'] ))
        {
            try
            {
                if (Auth::guard('customer')->attempt($credentials))
                {
                    //ceck cart session
                    if(null !== session('cart_product'))
                    {
                        $this->orderProductRepository->edit(session('cart_product'), [
                            'costumer_id' => Auth()->guard('customer')->user()->id
                        ]);
                        Session::forget('cart_product');
                    }

                    if(null !== session('cart_hampers'))
                    {
                        $this->orderHampersRepository->edit(session('cart_hampers'), [
                            'costumer_id' => Auth()->guard('customer')->user()->id
                        ]);
                        Session::forget('cart_hampers');
                    }
                    
                    return redirect()->route('shop-product.index')->with('toast_success', 'Halo selamat berbelanja');
                } 
                else 
                {
                    return redirect()->back()->with('toast_warning', 'Kredensial tidak valid');
                }
            } 
            catch (\Exception $e) 
            {
                return redirect()->back()->with('toast_warning', 'Terjadi kesalahan: ' . $e->getMessage());
            }
        }
        return back()->with('toast_warning', 'nama pengguna atau password tidak sesuai');

    }

    public function register(Request $request)
    {
        if(Auth::guard('customer')->check())
        {
            return redirect()->route('dashboard.customer');
        }
        
        $data = $request->validate([
                "name" => ['required', 'string', 'max:100'],
                "username" => ['required', 'string', 'unique:customers'],
                "email" => ['required', 'email', 'unique:customers'],
                "phone" => ['required', 'string'],
                "wa" => ['required', 'string'],
                "district_id" => ['required'],
                "tgl_lahir" => ['required', 'string'],
                "jenis_kelamin" => ['required', 'string'],
                "address" => ['required', 'string'],
                "password" => ['required', 'string', 'min:8', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/'],
                "password_confirmation" => ['required', 'same:password'],
            ], [], [
                "name" => "Nama",
                "username" => "Nama pengguna",
                "email" => "Email ",
                "phone" => "No Telepon",
                "wa" => "No Whatsapp",
                "district_id" => "Kabupaten / Kota",
                "tgl_lahir" => "Tanggal Lahir",
                "jenis_kelamin" => "Jenis Kelamin",
                "address" => "Alamat",
                "password" => "Password",
                "password_confirmation" => "Konfirmasi Password"
            ]);
        

            $token = sha1($data['email'] . now());
        
        try
        {
            $data['id']                       = 'CST-' . Helper::table_id();
            $data['is_active']                = '1';
            $data['created_by']               = $data['id'];
            $data['password']                 = Hash::make($data['password']);
            $data['verification_token']       = $token;
            $this->repository->store($data);

            Mail::to($data['email'])->send(new VerifyEmail($token));

            $discount = $this->discountRepository->getDiscNewCostumer()->toArray();

            //discount new cost
            $record_user['id'] = 'DNC-' . Helper::table_id();
            $record_user['costumer_id'] = $data['id'];
            $record_user['code_discount'] = $discount['code_discount'];
            $record_user['discount_id'] =  $discount['id'];
            $record_user['is_used']    =  '0';
            $record_user['created_by'] = $data['id'];
            $record_user['updated_by'] = $data['id'];
            $this->d_customer_repository->store($record_user);

            return redirect()->route('loginf.customer')->with('toast_success', "Silahkan verify email");
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

    public function verify($token)
    {
        try {
            $this->repository->verifyMail($token);
            return redirect()->route('loginf.customer')->with('toast_success', "Silahkan login aktivasi email anda berhasil");
            
        } catch (Exception $e) {
           return redirect()->route('loginf.customer')->with('toast_success', "Token yang anda masukkan mungkin sudah digunakan");
        }
    }

    public function logout()
    {
        Auth::guard('customer')->logout();
        return redirect()->route('loginf.customer');
    }
}
