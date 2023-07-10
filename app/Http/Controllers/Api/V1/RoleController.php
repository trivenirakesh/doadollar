<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Role;
use App\Traits\CommonTrait;
use App\Http\Resources\V1\RoleResource;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

class RoleController extends Controller
{
    use CommonTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $getRoleDetails = new Role;
        if(!empty($request->search)){
            $getRoleDetails = $getRoleDetails->where('name','like', "%".$request->search."%");
        }
        $orderColumn = 'id';
        $orderType = 'DESC';
        if($request->has('column')){
            $orderColumn = $request->column;
        }
        if($request->has('column')){
            $orderType = $request->type;
        }
        $getRoleDetails = $getRoleDetails->orderBy($orderColumn,$orderType)->pagnate(10);
        return RoleResource::collection($getRoleDetails); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validation section
        $validateUser = Validator::make($request->all(),
            [
                'name' => 'required',
            ],
            [
                'name.required' => 'Please enter name',
            ]
        );

        if ($validateUser->fails()) {
            return $this->errorResponse($validateUser->errors(), 401);
        }

        // get logged in user details 
        $getAdminDetails = auth('sanctum')->user();

        // Save entity section
        $roleName = preg_replace('/\s+/', ' ', ucwords(strtolower($request->name)));
        $role = new Role;
        $role->name = $roleName;
        $role->status = 1;
        if (!empty($getAdminDetails)) {
            $role->created_by = $getAdminDetails->id;
            $role->created_ip = '123';
        }
        $role->save();
        $lastId = $role->id;
        $getRoleDetails = RoleResource::collection(Role::where('id',$lastId)->get());
        return $this->successResponse($getRoleDetails, 'Role created successfully', 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $getRoleDetails = Role::where('id', $id)->get();
        if(count($getRoleDetails) > 0){
            return $this->successResponse(RoleResource::collection($getRoleDetails), 'Role details fetch successfully', 200);
        }else{
            return $this->errorResponse('Role not found', 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validation section
        $rules = [];
        $messages = [];
        if($request->has('name')){
            $rules['name'] = 'required';
            $messages['name.required'] = 'Please enter name';
        }
        if($request->has('status')){
            $rules['status'] = 'required';
            $messages['status.required'] = 'Please enter status';
        }
        $validateUser = Validator::make($request->all(),$rules,$messages);

        if ($validateUser->fails()) {
            return $this->errorResponse($validateUser->errors(), 401);
        }

        // get logged in user details 
        $getAdminDetails = auth('sanctum')->user();

        // Save entity section
        $role = Role::where('id',$id)->first();
        
        if(empty($role)){
            return $this->errorResponse('Role not found', 404);
        }
        if($request->has('name')){
            $roleName = preg_replace('/\s+/', ' ', ucwords(strtolower($request->name)));
            $role->name = $roleName;
        }
        if($request->has('status')){
            $role->status = $request->status;
        }
        if (!empty($getAdminDetails) && !empty($getAdminDetails->id)) {
            $role->updated_by = $getAdminDetails->id;
            $role->updated_ip = '';
        }
        $role->update();
        $getRoleDetails = RoleResource::collection(Role::where('id',$id)->get());
        return $this->successResponse($getRoleDetails, 'Role updated successfully', 200);
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // get logged in user details 
        $getAdminDetails = auth('sanctum')->user();

        // Delete entity
        $checkRoleData = Role::find($id);
        if (!empty($checkRoleData)) {
            $checkRoleData->deleted_by = $getAdminDetails->id;
            $checkRoleData->deleted_ip = '';
            $checkRoleData->update();
            $deleteRole = Role::find($id)->delete();
            if ($deleteRole) {
                return $this->successResponse([], 'Role deleted successfully', 200);
            }
        } else {
            return $this->errorResponse('Role not found', 404);
        }
    }
}
