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
        return $this->successResponseArr(self::module . __('messages.success.list'), $data);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Save entity section
        $roleName = preg_replace('/\s+/', ' ', ucwords(strtolower($request->name)));
        $role = new Role;
        $role->name = $roleName;
        $role->status = 1;
        $role->created_by = auth()->user()->id;
        $role->created_ip = CommonHelper::getUserIp();
        $role->save();
        $lastId = $role->id;
        $getRoleDetails = new RoleResource($role);
        return $this->successResponseArr(self::module . __('messages.success.create'), $getRoleDetails);
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
        if ($getRoleData == null) {
            return $this->errorResponseArr(self::module . __('messages.validation.not_found'));
        }
        $getRoleData = new RoleResource($getRoleData);
        return $this->successResponseArr(self::module . __('messages.success.details'), $getRoleData);
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

        $role = Role::where('id', $id)->first();
        if ($role == null) {
            return $this->errorResponseArr(self::module . __('messages.validation.not_found'));
        }
        if ($request->has('name')) {
            $roleName = preg_replace('/\s+/', ' ', ucwords(strtolower($request->name)));
            $role->name = $roleName;
        }
        if ($request->has('status')) {
            $role->status = $request->status;
        }
        $role->updated_by = auth()->user()->id;
        $role->updated_ip = CommonHelper::getUserIp();

        $role->update();
        $getRoleDetails = new RoleResource($role);
        return $this->successResponseArr(self::module . __('messages.success.update'), $getRoleDetails);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role =  Role::where('id', $id)->first();
        if ($role == null) {
            return $this->errorResponseArr(self::module . __('messages.validation.not_found'));
        }

        // Delete entity
        $role->deleted_by = auth()->user()->id;
        $role->deleted_ip = CommonHelper::getUserIp();
        $role->update();
        $deleteRole = $role->delete();
        if ($deleteRole) {
            return $this->successResponseArr(self::module . __('messages.success.delete'));
        }
    }
}
