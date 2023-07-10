<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\AuthController;
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


Route::post('/entity/login', [AuthController::class, 'loginEntity']);


Route::middleware('auth:sanctum')->group( function () {

    // logout route
    Route::post('entitylogout',[AuthController::class,'logout']);

    // Manage Role 
    Route::resource('roles',RoleController::class);

    // Manage Entity
    Route::get('entity',[UsersController::class,'index']);
    Route::get('entity/{id}',[UsersController::class,'show']);
    Route::post('entity',[UsersController::class,'create']);
    Route::post('entity/{id}',[UsersController::class,'update']);
    Route::delete('entity/{id}',[UsersController::class,'destroy']);
});