<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\AuthController;
use App\Http\Controllers\Api\V1\CampaignCategoryController;
use App\Http\Controllers\Api\V1\CampaignController;
use App\Http\Controllers\Api\V1\InquiryController;
use App\Http\Controllers\Api\V1\PaymentGatewaySettingController;
use App\Http\Controllers\Api\V1\SocialPlatformSettingController;
use App\Http\Controllers\Api\V1\UsersController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('login', [AuthController::class, 'loginEntity']);
Route::post('inquiry',[InquiryController::class,'store']);

Route::middleware('auth:sanctum')->group( function () {

    // Changed password & edit profile 
    Route::post('changepassword',[UsersController::class,'changePassword']);

    // logout route
    Route::post('logout',[AuthController::class,'logout']);

    // Manage Role 
    Route::resource('roles',RoleController::class)->except(['create','edit']);

    // Manage Entity
    Route::get('entity',[UsersController::class,'index']);
    Route::get('entity/{id}',[UsersController::class,'show']);
    Route::post('entity',[UsersController::class,'create']);
    Route::post('entity/{id}',[UsersController::class,'update']);
    Route::delete('entity/{id}',[UsersController::class,'destroy']);

    // Manage campaign category
    Route::get('campaigncategory',[CampaignCategoryController::class,'index']);
    Route::get('campaigncategory/{id}',[CampaignCategoryController::class,'show']);
    Route::post('campaigncategory',[CampaignCategoryController::class,'store']);
    Route::post('campaigncategory/{id}',[CampaignCategoryController::class,'update']);
    Route::delete('campaigncategory/{id}',[CampaignCategoryController::class,'destroy']);

    // Manage payment gateway setting
    Route::get('paymentgatewaysetting',[PaymentGatewaySettingController::class,'index']);
    Route::get('paymentgatewaysetting/{id}',[PaymentGatewaySettingController::class,'show']);
    Route::post('paymentgatewaysetting',[PaymentGatewaySettingController::class,'store']);
    Route::post('paymentgatewaysetting/{id}',[PaymentGatewaySettingController::class,'update']);
    Route::delete('paymentgatewaysetting/{id}',[PaymentGatewaySettingController::class,'destroy']);

    // Manage social platform setting
    Route::get('socialplatformsetting',[SocialPlatformSettingController::class,'index']);
    Route::get('socialplatformsetting/{id}',[SocialPlatformSettingController::class,'show']);
    Route::post('socialplatformsetting',[SocialPlatformSettingController::class,'store']);
    Route::post('socialplatformsetting/{id}',[SocialPlatformSettingController::class,'update']);
    Route::delete('socialplatformsetting/{id}',[SocialPlatformSettingController::class,'destroy']);

    // Manage Upload types 
    Route::resource('uploadtypes',UploadTypesController::class)->except(['create','edit']);;

    //Manage campaign
    Route::post('campaignslist',[CampaignController::class,'index']);
    Route::get('campaign/{id}',[CampaignController::class,'show']);
    Route::post('campaign',[CampaignController::class,'store']);
    Route::post('campaign/{id}',[CampaignController::class,'update']);
    Route::delete('campaign/{id}',[CampaignController::class,'destroy']);
    
    // Manage Email template 
    Route::resource('emailtemplates',EmailTemplatesController::class);

    // Manage Pages
    Route::resource('staticpages',StaticPageController::class);

    // Inquiry list
    Route::get('inquiry',[InquiryController::class,'index']);
});