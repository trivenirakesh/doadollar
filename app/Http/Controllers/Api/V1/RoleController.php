<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Role;
use App\Traits\CommonTrait;
use App\Http\Resources\V1\RoleResource;
use App\Helpers\CommonHelper;
use App\Http\Requests\RoleCreateUpdateRequest;
use App\Services\RoleService;
use Illuminate\Support\Facades\Cache;

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
    // public function index()
    // {
    //     return RoleResource::collection(Cache::remember('roles',60*60*24,function(){
    //         return Role::latest('id')->get();
    //     })); 
    // }
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

    // public function store(Request $request)
    // {
    //     // Validation section
    //     $validateUser = Validator::make(
    //         $request->all(),
    //         [
    //             'name' => 'required',
    //         ],
    //         [
    //             'name.required' => __('messages.validation.name'),
    //         ]
    //     );

    //     if ($validateUser->fails()) {
    //         return $this->errorResponse($validateUser->errors(), 401);
    //     }

    //     // get logged in user details 
    //     $getAdminDetails = auth('sanctum')->user();

    //     // Save entity section
    //     $roleName = preg_replace('/\s+/', ' ', ucwords(strtolower($request->name)));
    //     $role = new Role;
    //     $role->name = $roleName;
    //     $role->status = 1;
    //     $role->created_at = CommonHelper::getUTCDateTime(date('Y-m-d H:i:s'));
    //     if (!empty($getAdminDetails)) {
    //         $role->created_by = $getAdminDetails->id;
    //         $role->created_ip = CommonHelper::getUserIp();
    //     }
    //     $role->save();
    //     $lastId = $role->id;
    //     $getRoleData = $this->getRoleDetails($lastId, 0);
    //     $getRoleDetails = RoleResource::collection($getRoleData);
    //     return $this->successResponse($getRoleDetails, self::module . __('messages.success.create'), 201);
    // }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $getRoleDetails = $this->roleService->show($id);
        return $this->successResponse(new RoleResource($getRoleDetails['data']), self::module . __('messages.success.details'), 200);
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
        $getRoleDetails = $this->roleService->update($request, $id);
        return $this->successResponse($getRoleDetails['data'], self::module . __('messages.success.update'), 200);
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
