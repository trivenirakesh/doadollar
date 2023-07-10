<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Entitymst;
use App\Traits\CommonTrait;
use App\Http\Resources\V1\EntityResource;

class UsersController extends Controller
{
    use CommonTrait;

    public function index()
    {

        // get logged in user details 
        $getAuthDetails = auth('sanctum')->user();

        // get super admin & managers list
        $getEntityDetails = Entitymst::whereIn('entity_type', [0, 1]);

        if (!empty($getAuthDetails)) {
            $loggedAuthId = $getAuthDetails->id;
            $getEntityDetails = $getEntityDetails->whereNotIn('id', [$loggedAuthId]);
        }

        $getEntityDetails = $getEntityDetails->paginate(10);

        return EntityResource::collection($getEntityDetails);
    }

    public function show($id)
    {
        $getEntityDetails = Entitymst::where('id', $id)->get();
        if (count($getEntityDetails) > 0) {
            return EntityResource::collection($getEntityDetails);
        } else {
            return $this->errorResponse('User not found', 404);
        }
    }

    public function create(Request $request)
    {

        // Validation section
        $rules = [];
        $messages = [];
        $rules = [
            'entity_type' => 'required|digits:1|lte:3',
            'first_name' => 'required',
            'last_name' => 'required',
            'mobile' => 'required|numeric|digits:10|unique:entitymst,mobile,NULL,id,deleted_at,NULL',
            'email' => 'required|email|unique:entitymst,email,NULL,id,deleted_at,NULL',
            'password' => 'required'
        ];

        $messages = [
            'entity_type.required' => 'Please enter entity type',
            'entity_type.digits' => 'Entity type value must be numeric',
            'entity_type.lte' => 'Entity type value must between 0 and 3',
            'first_name.required' => 'Please enter first name',
            'last_name.required' => 'Please enter last name',
            'email.required' => 'Please enter email',
            'email.email' => 'Invaild email address',
            'email.unique' => 'Email address is already registered. Please, use a different email',
            'mobile.required' => 'Please enter mobile',
            'mobile.numeric' => 'Mobile must be numeric',
            'mobile.digits' => 'Mobile should be 10 digit number',
            'mobile.unique' => 'Mobile number is already registered. Please, use a different mobile',
            'password.required' => 'Please enter password',
        ];

        if ($request->has('role_id')) {
            $rules['role_id'] = 'required|numeric';
            $messages['role_id.required'] = 'Please enter role';
            $messages['role_id.numeric'] = 'Role value must be numeric';
        }
        $validateUser = Validator::make($request->all(), $rules, $messages);

        if ($validateUser->fails()) {
            return $this->errorResponse($validateUser->errors(), 400);
        }
    }
}
