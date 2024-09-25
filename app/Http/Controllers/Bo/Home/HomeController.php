<?php

namespace App\Http\Controllers\Bo\Home;

use Helper;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    protected $data = array();

    public function __construct()
    {
        $this->data['title'] = 'dashboard';
        $this->data['view_directory'] = "admin.feature.dashboard";
    }

    public function index()
    {
        $ref = $this->data;
        return view($this->data['view_directory'].'.index', compact('ref'));
    }
}
