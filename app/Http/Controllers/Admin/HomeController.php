<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CommonHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    public function login()
    {
        return view('admin/auth/login');
    }

    public function form()
    {
        return view('admin/sampleform');
    }

    public function list()
    {
        return view('admin/samplelist');
    }

    public function Logout()
    {
        Auth::logout();
        return \Redirect::to("admin/login")
            ->with('message', array('type' => 'success', 'text' => 'You have successfully logged out'));
    }
}
