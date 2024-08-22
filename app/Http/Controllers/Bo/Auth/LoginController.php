<?php

namespace App\Http\Controllers\Bo\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    protected $data = array();

    public function __construct()
    {
        $this->data['title'] = 'login';
        $this->data['view_directory'] = "admin.feature.auth";
    }

    public function index()
    {
        if(Auth::check())
        {
            return redirect()->route('home');
        }

        $ref = $this->data;
        return view($this->data['view_directory'].'.index', compact('ref'));
    }

    public function auth(Request $request)
    {
        if(Auth::check())
        {
            return redirect()->route('home');
        }
        
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            return redirect()->route('home')->with('success', 'Halo selamat datang '.auth()->user()->name);
        }
        return back()->with('error', config('error','nama pengguna atau password tidak sesuai'));
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        return redirect()->route('login');
    }
}
