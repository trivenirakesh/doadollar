<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('admin/home');
    }

    public function login()
    {
        return view('admin/auth/login');
    }

    public function form(){
        return view('admin/sampleform');
    }

    public function list(){
        return view('admin/samplelist');
    }
}
