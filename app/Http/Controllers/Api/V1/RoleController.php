<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Traits\CommonTrait;
use App\Http\Resources\V1\RoleResource;
use App\Http\Requests\RoleCreateUpdateRequest;
use App\Services\RoleService;

class RoleController extends Controller
{
    use CommonTrait;
    const module = 'Role';

    private $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles =  $this->roleService->index() ?? [];
        return $roles;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoleCreateUpdateRequest $request)
    {
        $getRoleDetails  = $this->roleService->store($request);
        return $getRoleDetails;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $getRoleDetails = $this->roleService->show($id);
        if(!empty($getRoleDetails['data'])){
            return $this->successResponse(new RoleResource($getRoleDetails['data']), $getRoleDetails['message'], 200);
        }else{
            return $this->successResponse($getRoleDetails['data'], $getRoleDetails['message'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RoleCreateUpdateRequest $request, $id)
    {
        $getRoleDetails = $this->roleService->update($request, $id);
        return $this->successResponse($getRoleDetails['data'], $getRoleDetails['message'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = $this->roleService->destroy($id);
        if ($result['status'] == true) {
            return $this->successResponse([], $result['message'], 200);
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
