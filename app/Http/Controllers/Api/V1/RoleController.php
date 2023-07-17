<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Role;
use App\Traits\CommonTrait;
use App\Http\Resources\V1\RoleResource;
use App\Helpers\CommonHelper;

class RoleController extends Controller
{
    use CommonTrait;
    const module = 'Role';
    
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
        $getRoleDetails = $getRoleDetails->orderBy($orderColumn,$orderType)->paginate(10);
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
                'name.required' => __('messages.validation.name'),
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
        $role->created_at = CommonHelper::getUTCDateTime(date('Y-m-d H:i:s'));
        if (!empty($getAdminDetails)) {
            $role->created_by = $getAdminDetails->id;
            $role->created_ip = CommonHelper::getUserIp();
        }
        $role->save();
        $lastId = $role->id;
        $getRoleData = $this->getRoleDetails($lastId,0);
        $getRoleDetails = RoleResource::collection($getRoleData);
        return $this->successResponse($getRoleDetails, self::module.__('messages.success.create'), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $getRoleDetails = $this->getRoleDetails($id,0);
        return $this->successResponse(RoleResource::collection($getRoleDetails), self::module.__('messages.success.details'), 200);
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
        // check role exist or not 
        $checkRole = $this->getRoleDetails($id,1);

        // Validation section
        $rules = [];
        $messages = [];
        if($request->has('name')){
            $rules['name'] = 'required';
            $messages['name.required'] = __('messages.validation.name');
        }
        if($request->has('status')){
            $rules['status'] = 'required|numeric|lte:1';
            $messages['status.required'] = __('messages.validation.status');
            $messages['status.numeric'] = 'Status'.__('messages.validation.must_numeric');
            $messages['status.lte'] = __('messages.validation.status_lte');
        }
        $validateUser = Validator::make($request->all(),$rules,$messages);

        if ($validateUser->fails()) {
            return $this->errorResponse($validateUser->errors(), 401);
        }

        // get logged in user details 
        $getAdminDetails = auth('sanctum')->user();

        // Save entity section
        $role = $checkRole;
        if($request->has('name')){
            $roleName = preg_replace('/\s+/', ' ', ucwords(strtolower($request->name)));
            $role->name = $roleName;
        }
        if($request->has('status')){
            $role->status = $request->status;
        }
        $role->updated_at = CommonHelper::getUTCDateTime(date('Y-m-d H:i:s'));
        if (!empty($getAdminDetails) && !empty($getAdminDetails->id)) {
            $role->updated_by = $getAdminDetails->id;
            $role->updated_ip = CommonHelper::getUserIp();
        }
        $role->update();
        $getRoleData = $this->getRoleDetails($id,0);
        $getRoleDetails = RoleResource::collection($getRoleData);
        return $this->successResponse($getRoleDetails, self::module.__('messages.success.update'), 200);
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // check role exist or not 
        $checkRole = $this->getRoleDetails($id,1);

        // get logged in user details 
        $getAdminDetails = auth('sanctum')->user();

        // Delete entity
        $checkRoleData = $checkRole;
        if (!empty($checkRoleData)) {
            // $checkRoleData->deleted_at = CommonHelper::getUTCDateTime(date('Y-m-d H:i:s'));
            $checkRoleData->deleted_by = $getAdminDetails->id;
            $checkRoleData->deleted_ip = CommonHelper::getUserIp();
            $checkRoleData->update();
            $deleteRole = Role::find($id)->delete();
            if ($deleteRole) {
                return $this->successResponse([], self::module.__('messages.success.delete'), 200);
            }
        }
    }

    public function getRoleDetails($id,$type){
        $getRoleData = Role::where('id',$id);
        if($type == 1){
            $getRoleData = $getRoleData->first();
            if(!empty($getRoleData)){
                return $getRoleData;
            }else{
                throw new \ErrorException(self::module.__('messages.validation.not_found'));
            }
        }else{
            $getRoleData = $getRoleData->get();
            if(count($getRoleData) > 0){
                return $getRoleData;
            }else{
                throw new \ErrorException(self::module.__('messages.validation.not_found'));
            }
        }
        
    }
}
