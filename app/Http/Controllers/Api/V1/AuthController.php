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
                'email.required' => 'Please enter email',
                'email.email' => 'Invaild email address',
                'password.required'    => 'Please enter password',
            ]);

            if($validateUser->fails()){
                return $this->errorResponse($validateUser->errors(), 401);
            }

            if(!Auth::attempt($request->only(['email', 'password']))){
                return $this->errorResponse('Email or password you entered did not match our records.',401);
            }
            $user = Entitymst::where('email', $request->email)->first();
            $user->tokens()->delete();
            $getUserDetails['id'] = $user->id;
            $getUserDetails['username'] = $user->first_name.' '.$user->last_name;
            $getUserDetails['email'] = $user->email;
            $getUserDetails['mobile'] = $user->mobile;
            $getUserDetails['status'] = ($user->status == 1 ? 'Active' : 'Deactive');
            $getUserDetails['token'] = $user->createToken("api")->plainTextToken;
            
            return $this->successResponse($getUserDetails,'User successfully logged in',200);

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
             'id.required'    => 'Please enter id',
             'id.numeric'    => 'Id must be numeric',
            ]);

            if($validateUser->fails()){
                return $this->errorResponse($validateUser->errors(), 401);
            }
            $user = Entitymst::find($request->id);
            if(!$user){
                return $this->errorResponse('User not found', 404);
            }
            $user->tokens()->delete();
            if($user){
                return $this->successResponse([],'User logout successfully',200);
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->errorResponse($th->getMessage(),500);
        }
    }
}