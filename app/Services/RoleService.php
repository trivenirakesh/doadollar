<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Traits\CommonTrait;
use App\Http\Resources\V1\RoleResource;
use App\Helpers\CommonHelper;
use Illuminate\Support\Facades\Cache;

class RoleService
{
    use CommonTrait;
    const module = 'Role';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data =  (Cache::remember('roles', 60 * 60 * 24, function () {
            return RoleResource::collection(Role::latest('id')->get());
        }));
        return ['status' => true, 'message' => 'success', 'data' => $data];
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // get logged in user details 
        $getAdminDetails = auth()->user();
        // Save entity section
        $roleName = preg_replace('/\s+/', ' ', ucwords(strtolower($request->name)));
        $role = new Role;
        $role->name = $roleName;
        $role->status = 1;
        if (!empty($getAdminDetails)) {
            $role->created_by = $getAdminDetails->id;
            $role->created_ip = CommonHelper::getUserIp();
        }
        $role->save();
        $lastId = $role->id;
        $getRoleData = $this->getRoleDetails($lastId, 0);
        $getRoleDetails = RoleResource::collection($getRoleData);
        return ['status' => true, 'message' => self::module . __('messages.success.create'), 'data' => $getRoleDetails];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $getRoleData = Role::where('id', $id)->first();
        if ($getRoleData != null) {
            $data = $getRoleData;
            return  ['status' => true, 'data' => $data];
        } else {
            return ['status' => false, 'message' => self::module . __('messages.validation.not_found')];
        }
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
        $checkRole = $this->getRoleDetails($id, 1);

        // get logged in user details 
        $getAdminDetails = auth()->user();

        // Save entity section
        $role = $checkRole;
        if ($request->has('name')) {
            $roleName = preg_replace('/\s+/', ' ', ucwords(strtolower($request->name)));
            $role->name = $roleName;
        }
        if ($request->has('status')) {
            $role->status = $request->status;
        }
        $role->updated_at = CommonHelper::getUTCDateTime(date('Y-m-d H:i:s'));
        if (!empty($getAdminDetails) && !empty($getAdminDetails->id)) {
            $role->updated_by = $getAdminDetails->id;
            $role->updated_ip = CommonHelper::getUserIp();
        }
        $role->update();
        $getRoleData = $this->getRoleDetails($id, 1);
        $getRoleDetails = new RoleResource($getRoleData);
        return ['status' => true, 'message' => self::module . __('messages.success.update'), 'data' => $getRoleDetails];
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
        $checkRole = $this->getRoleDetails($id, 1);

        // get logged in user details 
        $getAdminDetails = auth()->user();

        // Delete entity
        $checkRoleData = $checkRole;
        if (!empty($checkRoleData)) {
            // $checkRoleData->deleted_at = CommonHelper::getUTCDateTime(date('Y-m-d H:i:s'));
            $checkRoleData->deleted_by = $getAdminDetails->id;
            $checkRoleData->deleted_ip = CommonHelper::getUserIp();
            $checkRoleData->update();
            $deleteRole = Role::find($id)->delete();
            if ($deleteRole) {
                return ['status' => true, 'message' => self::module . __('messages.success.delete')];
            }
        } else {
            return ['status' => false, 'message' => self::module . __('messages.validation.not_found')];
        }
    }

    public function getRoleDetails($id, $type)
    {
        $getRoleData = Role::where('id', $id);
        if ($type == 1) {
            $getRoleData = $getRoleData->first();
            if (!empty($getRoleData)) {
                return $getRoleData;
            } else {
                throw new \ErrorException(self::module . __('messages.validation.not_found'));
            }
        } else {
            $getRoleData = $getRoleData->get();
            if (count($getRoleData) > 0) {
                return $getRoleData;
            } else {
                throw new \ErrorException(self::module . __('messages.validation.not_found'));
            }
        }
    }
}
