<?php

use App\Http\Controllers\Admin\CampaignCategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{
    HomeController,
    ProfileController,
    PaymentGatewaySettingController,
    SocialPlatformSettingController,
    UserController,
    StaticPageController,
    CampaignController
};
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

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'admin', 'revalidate'], 'as' => 'admin.'], function () {
    Route::get('/', [HomeController::class, 'index'])->name('dashboard');
    Route::get('logout', [HomeController::class, 'logout'])->name('logout');
    Route::resource('setting/payment-gateway', PaymentGatewaySettingController::class)->except(['edit', 'update']);
    Route::resource('campaign/category', CampaignCategoryController::class, ['names' => 'campaign-category'])->except(['edit', 'update']);
    Route::resource('campaigns', CampaignController::class);
    Route::resource('users', UserController::class)->except(['edit', 'update']);
    Route::resource('setting/social-media', SocialPlatformSettingController::class)->except(['edit', 'update']);

    Route::get('static-page/{slug}', [StaticPageController::class, 'index'])->name('static_page');
    Route::post('update-static-page', [StaticPageController::class, 'store'])->name('static_page_update');

    Route::get('profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('profile', [ProfileController::class, 'update'])->name('profile-update');
    Route::post('update-password', [ProfileController::class, 'updatePassword'])->name('update-password');
});
