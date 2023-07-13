<?php

namespace App\Http\Controllers\API\V1;

use App\Models\Entitymst;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\EntityResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Traits\CommonTrait;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    use CommonTrait;
    
    /**
     * Login The Entity
     * @param Request $request
     * @return Entitymst
     */
    public function loginEntity(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(), 
            [
                'email' => 'required|email',
                'password' => 'required'
            ],
            [
                'email.required' => __('messages.validation.email'),
                'email.email' => __('messages.validation.email_email'),
                'password.required'    => __('messages.validation.password'),
            ]);

            if($validateUser->fails()){
                return $this->errorResponse($validateUser->errors(), 401);
            }

            if(!Auth::attempt($request->only(['email', 'password']))){
                return $this->errorResponse( __('messages.validation.email_password_wrong') ,401);
            }
            $user = Entitymst::where('email', $request->email)->first();
            $user->tokens()->delete();
            $getUserDetails['id'] = $user->id;
            $getUserDetails['username'] = $user->first_name.' '.$user->last_name;
            $getUserDetails['email'] = $user->email;
            $getUserDetails['mobile'] = $user->mobile;
            $getUserDetails['status'] = ($user->status == 1 ? 'Active' : 'Deactive');
            $getUserDetails['token'] = $user->createToken("api")->plainTextToken;
            
            return $this->successResponse($getUserDetails, __('messages.success.user_login') ,200);

        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->errorResponse($th->getMessage(),500);
        }
    }

    public function logout(Request $request){
        try {
            $validateUser = Validator::make($request->all(), [
                'id' => 'required|numeric',
            ],
            [
             'id.required'    => __('messages.validation.id') ,
             'id.numeric'    => __('messages.validation.id_numeric') ,
            ]);

            if($validateUser->fails()){
                return $this->errorResponse($validateUser->errors(), 401);
            }
            $user = Entitymst::find($request->id);
            if(!$user){
                return $this->errorResponse( __('messages.success.user_not_found') , 404);
            }
            $user->tokens()->delete();
            if($user){
                return $this->successResponse([] , __('messages.success.user_logout') ,200);
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->errorResponse($th->getMessage(),500);
        }
    }
}