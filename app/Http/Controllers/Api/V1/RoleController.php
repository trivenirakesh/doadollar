<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\CommonTrait;
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

    public function index()
    {
        $roles =  $this->roleService->index() ?? [];
        if ($roles['status'] == false) {
            return response()->json($roles, 401);
        }
        return response()->json($roles, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoleCreateUpdateRequest $request)
    {
        $role  = $this->roleService->store($request);
        if ($role['status'] == false) {
            return response()->json($role, 401);
        }
        return response()->json($role, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = $this->roleService->show($id);
        if ($role['status'] == false) {
            return response()->json($role, 401);
        }
        return response()->json($role, 200);
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
        $role = $this->roleService->update($request, $id);
        if ($role['status'] == false) {
            return response()->json($role, 401);
        }
        return response()->json($role, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = $this->roleService->destroy($id);
        if ($role['status'] == false) {
            return response()->json($role, 401);
        }
        return response()->json($role, 200);
    }
}
