<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Entitymst;
use App\Models\Role;
use App\Traits\CommonTrait;
use App\Http\Resources\V1\EntityResource;
use Illuminate\Support\Facades\Hash;
use App\Helpers\CommonHelper;

class UsersController extends Controller
{
    use CommonTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        // get logged in user details 
        $getAuthDetails = auth('sanctum')->user();

        // get super admin & managers list
        $getUserDetails = new Entitymst;

        if (!empty($getAuthDetails)) {
            $loggedAuthId = $getAuthDetails->id;
            $getUserDetails = $getUserDetails->whereNotIn('id', [$loggedAuthId]);
        }
        if(!empty($request->search)){
            $getUserDetails = $getUserDetails->where('name','like', "%".$request->search."%");
        }
        $orderColumn = 'id';
        $orderType = 'DESC';
        if($request->has('column')){
            $orderColumn = $request->column;
        }
        if($request->has('column')){
            $orderType = $request->type;
        }
        $getUserDetails = $getUserDetails->orderBy($orderColumn,$orderType)->paginate(10);

        return EntityResource::collection($getUserDetails);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $getUserDetails = $this->getUserDetails($id,0);
        if (count($getUserDetails) > 0) {
            return EntityResource::collection($getUserDetails);
        } else {
            return $this->errorResponse('User not found', 404);
        }
    }

    /**
     * Store a newly created entity in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        // Validation section
        $rules = [];
        $messages = [];
        $rules = [
            'entity_type' => 'required|digits:1|lte:2',
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:entitymst,email,NULL,id,deleted_at,NULL',
            'mobile' => 'required|numeric|digits:10|unique:entitymst,mobile,NULL,id,deleted_at,NULL',
            'password' => 'required'
        ];

        $messages = [
            'entity_type.required' => 'Please enter entity type',
            'entity_type.digits' => 'Entity type value must be numeric',
            'entity_type.lte' => 'Entity type value must between 0 and 2',
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

        if ($request->has('role_id') && $request->entity_type != 2) {
            $rules['role_id'] = 'required|numeric';
            $messages['role_id.required'] = 'Please enter role';
            $messages['role_id.numeric'] = 'Role value must be numeric';
        }
        $validateUser = Validator::make($request->all(), $rules, $messages);

        if ($validateUser->fails()) {
            return $this->errorResponse($validateUser->errors(), 400);
        }

        // save details 
        $password = $request->password;
        $uppercase = preg_match('@[A-Z]@', $password);
        $lowercase = preg_match('@[a-z]@', $password);
        $number    = preg_match('@[0-9]@', $password);
        $specialChars = preg_match('@[^\w]@', $password);
        if(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
            $errorMessage = 'Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.';
            return $this->errorResponse($errorMessage, 400);
        }
        
        // remoeve blank spaces from string 
        $firstName = ucfirst(strtolower(str_replace(' ', '',$request->first_name))); 
        $lastName = ucfirst(strtolower(str_replace(' ', '',$request->last_name))); 

        $createUser = new Entitymst();
        $createUser->first_name = $firstName;
        $createUser->last_name = $lastName;
        $createUser->email = str_replace(' ', '',$request->email);
        $createUser->mobile = $request->mobile;
        $createUser->entity_type = $request->entity_type;
        $createUser->password = Hash::make($request->password);
        $createUser->created_at = CommonHelper::getUTCDateTime(date('Y-m-d H:i:s'));
        if (isset($request->role_id) && $request->entity_type != 2) {
            $checkRoleExist = Role::find($request->role_id );
            if (!$checkRoleExist) {
                return $this->errorResponse('Role not found', 404);
            }
            $createUser->role_id = $request->role_id;
        }

        // get logged in user details 
        $getAdminDetails = auth('sanctum')->user();
        if (!empty($getAdminDetails) && !empty($getAdminDetails->id)) {
            $createUser->created_by = $getAdminDetails->id;
            $createUser->created_ip = CommonHelper::getUserIp();
        }
        $createUser->save();
        $lastId = $createUser->id;
        $getUserDetails = $this->getUserDetails($lastId,0);
        $getUserDetails = EntityResource::collection($getUserDetails);
        return $this->successResponse($getUserDetails, 'User created successfully', 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id){
        
        // check user exist or not 
        $checkUser = $this->getUserDetails($id,1);
        if(empty($checkUser)){
            return $this->errorResponse('User not found', 400);
        }

        $userType = $checkUser->entity_type;
        // Validation section
        $rules = [];
        $messages = [];
        $rules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:entitymst,email,'.$id.',id,deleted_at,NULL',
            'mobile' => 'required|numeric|digits:10|unique:entitymst,mobile,'.$id.',id,deleted_at,NULL',
        ];

        $messages = [
            'first_name.required' => 'Please enter first name',
            'last_name.required' => 'Please enter last name',
            'email.required' => 'Please enter email',
            'email.email' => 'Invaild email address',
            'email.unique' => 'Email address is already registered. Please, use a different email',
            'mobile.required' => 'Please enter mobile',
            'mobile.numeric' => 'Mobile must be numeric',
            'mobile.digits' => 'Mobile should be 10 digit number',
            'mobile.unique' => 'Mobile number is already registered. Please, use a different mobile'
        ];

        if ($request->has('status')) {
            $rules['status'] = 'required|numeric|lte:1';
            $messages['status.required'] = 'Please enter status';
            $messages['status.numeric'] = 'Status value must be numeric';
            $messages['status.lte'] = 'Status should be 0 or 1';
        }
        if ($request->has('role_id') && $userType != 2) {
            $rules['role_id'] = 'required|numeric';
            $messages['role_id.required'] = 'Please enter role';
            $messages['role_id.numeric'] = 'Role value must be numeric';
        }
        $validateUser = Validator::make($request->all(), $rules, $messages);

        if ($validateUser->fails()) {
            return $this->errorResponse($validateUser->errors(), 400);
        }

        // remoeve blank spaces from string 
        $firstName = ucfirst(strtolower(str_replace(' ', '',$request->first_name))); 
        $lastName = ucfirst(strtolower(str_replace(' ', '',$request->last_name))); 

        // update details 
        $updateUser = $checkUser;
        $updateUser->first_name = $firstName;
        $updateUser->last_name = $lastName;
        $updateUser->email = str_replace(' ', '',$request->email);
        $updateUser->mobile = $request->mobile;
        $updateUser->entity_type = $request->entity_type;
        $updateUser->status = $request->status;
        $updateUser->password = Hash::make($request->password);
        $updateUser->updated_at = CommonHelper::getUTCDateTime(date('Y-m-d H:i:s'));
        if (isset($request->role_id) && $request->entity_type != 2) {
            $checkRoleExist = Role::find($request->role_id );
            if (!$checkRoleExist) {
                return $this->errorResponse('Role not found', 404);
            }
            $updateUser->role_id = $request->role_id;
        }

        // get logged in user details 
        $getAdminDetails = auth('sanctum')->user();
        if (!empty($getAdminDetails) && !empty($getAdminDetails->id)) {
            $updateUser->updated_by = $getAdminDetails->id;
            $updateUser->updated_ip = CommonHelper::getUserIp();
        }
        $updateUser->update();

        $getUserDetails = $this->getUserDetails($id,0);
        $getUserDetails = EntityResource::collection($getUserDetails);
        return $this->successResponse($getUserDetails, 'User updated successfully', 201);
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){

        // check user exist or not 
        $checkUser = $this->getUserDetails($id,1);
        if(empty($checkUser)){
            return $this->errorResponse('User not found', 400);
        }

        // get logged in user details 
        $getAdminDetails = auth('sanctum')->user();

        // Delete entity
        $checkUserData = $checkUser;
        if (!empty($checkUserData)) {
            $checkUserData->deleted_by = $getAdminDetails->id;
            $checkUserData->deleted_at = CommonHelper::getUTCDateTime(date('Y-m-d H:i:s'));
            $checkUserData->deleted_ip = CommonHelper::getUserIp();
            $checkUserData->update();
            $deleteEntity = Entitymst::find($id)->delete();
            if ($deleteEntity) {
                return $this->successResponse([], 'User deleted successfully', 200);
            }
        }
    }

    /**
     * Get user details based on id
     */
    public function getUserDetails($id,$type){
        
        $getUserDetails = Entitymst::where('id',$id);
        if($type == 1){
            $getUserDetails = $getUserDetails->first();
        }else{
            $getUserDetails = $getUserDetails->get();
        }
        return $getUserDetails;
    }

    /**
     * Changed password for entity.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function changePassword(Request $request){
        
        // Validation section
        $rules = [];
        $messages = [];
        $rules = [
            'id' => 'required',
            'old_password' => 'required',
            'new_password' => 'required'
        ];

        $messages = [
            'id.required' => 'Please enter id',
            'old_password.required' => 'Please enter old password',
            'new_password.required' => 'Please enter new password'
        ];

        $validateUser = Validator::make($request->all(), $rules, $messages);

        if ($validateUser->fails()) {
            return $this->errorResponse($validateUser->errors(), 400);
        }

        // check user exist or not 
        $userId = $request->id;
        $checkUser = $this->getUserDetails($userId,1);
        if(empty($checkUser)){
            return $this->errorResponse('User not found', 400);
        }

        //Match The Old Password
        $getAdminDetails = auth('sanctum')->user();
        if(!Hash::check($request->old_password, $getAdminDetails->password)){
            return $this->errorResponse("Old Password Doesn't match", 400);
        }

        $password = $request->new_password;
        $uppercase = preg_match('@[A-Z]@', $password);
        $lowercase = preg_match('@[a-z]@', $password);
        $number    = preg_match('@[0-9]@', $password);
        $specialChars = preg_match('@[^\w]@', $password);
        if(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
            $errorMessage = 'Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.';
            return $this->errorResponse($errorMessage, 400);
        }

        $updateUserPassword = $checkUser;
        $updateUserPassword->password = Hash::make($request->new_password);
        $updateUserPassword->update();
        if($updateUserPassword){
            return $this->successResponse([], 'Password reset successfully', 200);
        }
    }
}
