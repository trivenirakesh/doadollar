<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\RoleController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Auth::routes();
// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Route::get('admindashboard', [HomeController::class, 'dashboard'])->name('admin_user.dashboard');
// Route::get('adminlogin', [HomeController::class, 'login']);
// Route::get('form',[HomeController::class,'form']);
// Route::get('datatablelist',[HomeController::class,'list']);
Route::group(['prefix' => 'admin'], function () {
    Auth::routes(['register' => false]);
});

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'admin'], 'as' => 'admin.'], function () {
    Route::get('/', [HomeController::class, 'index'])->name('dashboard');
    Route::get('logout', [HomeController::class, 'logout'])->name('logout');

    // Route::resource('roles', RoleController::class);
});
