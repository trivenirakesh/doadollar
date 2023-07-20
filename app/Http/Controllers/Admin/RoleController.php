<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleCreateUpdateRequest;
use App\Services\RoleService;
use App\Traits\CommonTrait;

class RoleController extends Controller
{
    const module = 'Role';
    use CommonTrait;
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
        // try {
        $module  = 'Roles';
        $roles =  $this->roleService->index()['data'] ?? [];
        return view('demo_admin.roles.index', compact('roles', 'module'));
        // } catch (\Throwable $th) {
        // }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $module  = 'Roles Create';
        return view('demo_admin.roles.create', compact('module'));
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
        if ($getRoleDetails['status'] == false) {
            return view('demo_admin.roles.index',)->with('error', 'Something went wrong!!');
        }
        return redirect()->route('admin.roles.index')
            ->with('success', self::module . __('messages.success.create'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $module  = 'Role Detail';
        $role  = $this->roleService->show($id);
        if ($role['status'] == false) {
            return  redirect()->route('admin.roles.index',)->with('error', $role['message']);
        }
        $role = $role['data'] ?? '';
        return view('demo_admin.roles.show', compact('role', 'module'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $module  = 'Role Edit';
        $role  = $this->roleService->show($id);
        if ($role['status'] == false) {
            return redirect()->route('admin.roles.index',)->with('error', $role['message']);
        }
        $role = $role['data'] ?? '';
        return view('demo_admin.roles.edit', compact('role', 'module'));
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
        if ($getRoleDetails['status'] == false) {
            return redirect()->route('admin.roles.index',)->with('error', 'Something went wrong!!');
        }
        return redirect()->route('admin.roles.index',)->with('success', $getRoleDetails['message']);
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
        if ($result['status'] == false) {
            return redirect()->route('admin.roles.index',)->with('error', 'Something went wrong!!');
        }
        return redirect()->route('admin.roles.index',)->with('success', $result['message']);
    }
}
