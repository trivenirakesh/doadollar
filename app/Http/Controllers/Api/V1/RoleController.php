<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\RoleCreateUpdateRequest;
use App\Services\V1\RoleService;

class RoleController extends Controller
{

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
        if (!$roles['status']) {
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
        if (!$role['status']) {
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
        if (!$role['status']) {
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
        if (!$role['status']) {
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
        if (!$role['status']) {
            return response()->json($role, 401);
        }
        return response()->json($role, 200);
    }
}
