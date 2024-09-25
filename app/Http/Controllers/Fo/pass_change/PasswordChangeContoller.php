<?php

namespace App\Http\Controllers\fo\pass_change;

use App\Repositories\ContactUsRepository;
use App\Repositories\AboutUsRepository;
use App\Repositories\CustomerRepository;
use App\Repositories\OrderRepository;
use App\Repositories\OrderHampersRepository;

use Illuminate\Support\Facades\Mail;
use App\Mail\ChangePasswordMail;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Http\FormRequest;

use \App\Models\BannerView as BannerModel;

class PasswordChangeContoller extends Controller
{
    private ContactUsRepository $contactUsRepository;
    private AboutUsRepository $aboutUsRepository;
    private CustomerRepository $repository;

    protected $data = array();
    public function __construct(CustomerRepository $repository, ContactUsRepository $contactUsRepository, AboutUsRepository $aboutUsRepository)
    {
        $this->contactUsRepository = $contactUsRepository;
        $this->aboutUsRepository = $aboutUsRepository;
        $this->repository = $repository;
        $this->data['title'] = 'Change Password';
        $this->data['view_directory'] = "guest.feature.change_password";
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(Auth::guard('customer')->check())
        {
            return redirect()->route('dashboard.customer');
        }

        $ref = $this->data;
        $data['contact'] = $this->contactUsRepository->getById('1');
        $data['about'] = $this->aboutUsRepository->getById('1');

        $data['banner'] = BannerModel::first()->pluck('images');

        return view($this->data['view_directory'] . '.index', compact('ref', 'data'));
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
        $data = $request->validate([
            'email' => ['required'],
        ]);


        if(null !== $this->repository->getChecklogin($data['email'] ))
        {
            //update token
            $token = sha1($data['email'] . now());
            $this->repository->token_reset_pass($data['email'], $token);
            Mail::to($data['email'])->send(new ChangePasswordMail($token));
        }

        return redirect()->back()->with('toast_success', 'Silahkan Cek Email Anda');
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
    public function edit(string $token)
    {
        //menerima request
        if(Auth::guard('customer')->check())
        {
            return redirect()->route('dashboard.customer');
        }
        $ref = $this->data;
        $data['contact'] = $this->contactUsRepository->getById('1');
        $data['about'] = $this->aboutUsRepository->getById('1');
        $data['url'] = route('change-password.update', $token);

        $data['banner'] = BannerModel::first()->pluck('images');

        return view($this->data['view_directory'] . '.form', compact('ref', 'data'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $token)
    {
        if(Auth::guard('customer')->check())
        {
            return redirect()->route('dashboard.customer');
        }
        
        if($token == 'used')
        {
            return redirect()->route('loginf.customer')->with('toast_success', "Berhasil mengupdate password");
        }
        //mengambil id dari check
        $customer = $this->repository->search_reset_pass($token);

        //mengupdate password dan mengoper ke login
        $data = $request->validate([
                "password" => ['required', 'string', 'min:8', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/'],
                "password_confirmation" => ['required', 'same:password'],
            ], [], [
                "password" => "Password",
                "password_confirmation" => "Konfirmasi Password"
            ]);
        try
        {
            $data['password'] = Hash::make($data['password']);
            unset($data['password_confirmation']);
            $data['reset_password_token'] = 'used';
            $this->repository->edit($customer['id'], $data);

            return redirect()->route('loginf.customer')->with('toast_success', "Berhasil mengupdate password");
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
