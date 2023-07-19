<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\HomeController;

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

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('adminlogin',[HomeController::class,'login'])->name('admin.login');
Route::post('adminlogin',[HomeController::class,'checkLogin']); // check login
Route::get('form',[HomeController::class,'form']);
Route::get('datatablelist',[HomeController::class,'list']);

Route::group(['middleware' => ['setHeaders.token.web', 'auth:sanctum']], function () {
    Route::get('/logout',[HomeController::class, 'logout'])->name('admin.logout'); // Logout
    Route::get('dashboard',[HomeController::class, 'index'])->name('admin.dashboard');
});