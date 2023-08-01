<?php

namespace App\Services\V1;

use Illuminate\Http\Request;
use App\Models\Entitymst;
use App\Traits\CommonTrait;
use App\Http\Resources\V1\EntityResource;
use Illuminate\Support\Facades\Hash;
use App\Helpers\CommonHelper;
use App\Http\Resources\V1\EntityDetailResource;
use App\Models\Role;

class UserService
{
    use CommonTrait;
    const module = 'User';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $getUserDetails = Entitymst::whereNotIn('id', [auth()->user()->id])->paginate(10);
        $data =  EntityResource::collection($getUserDetails);
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
        // save details 
        $createUser = new Entitymst();
        $createUser->first_name = $request->first_name;
        $createUser->last_name = $request->last_name;
        $createUser->email = str_replace(' ', '', $request->email);
        $createUser->mobile = $request->mobile;
        $createUser->entity_type = $request->entity_type;
        $createUser->password = Hash::make($request->password);
        $createUser->status = 1;
        $createUser->role_id = $request->role_id;
        $createUser->created_by = auth()->user()->id;
        $createUser->created_ip = CommonHelper::getUserIp();
        $createUser->save();
        $getUserDetails = new EntityResource($createUser);
        return $this->successResponseArr(self::module . __('messages.success.create'), $getUserDetails);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $getUserData = Entitymst::where('id', $id)->first();
        if ($getUserData == null) {
            return $this->errorResponseArr(self::module . __('messages.validation.not_found'));
        }
        $getUserData = new EntityDetailResource($getUserData);
        return $this->successResponseArr(self::module . __('messages.success.details'), $getUserData);
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
        $updateUser = Entitymst::where('id', $id)->first();
        if ($updateUser == null) {
            return $this->errorResponseArr(self::module . __('messages.validation.not_found'));
        }

        // update details 
        $updateUser->first_name = $request->first_name;
        $updateUser->last_name = $request->last_name;
        $updateUser->email = str_replace(' ', '', $request->email);
        $updateUser->mobile = $request->mobile;
        $updateUser->status = $request->status;
        $updateUser->role_id = $request->role_id;
        $updateUser->updated_by = auth()->user()->id;
        $updateUser->updated_ip = CommonHelper::getUserIp();
        $updateUser->update();
        $getUserDetails = new EntityResource($updateUser);
        return $this->successResponseArr(self::module . __('messages.success.update'), $getUserDetails);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $userDetails = Entitymst::where('id', $id)->first();
        if ($userDetails == null) {
            return $this->errorResponseArr(self::module . __('messages.validation.not_found'));
        }
        // Delete user
        $userDetails->deleted_by = auth()->user()->id;
        $userDetails->deleted_ip = CommonHelper::getUserIp();
        $userDetails->update();
        $deleteUser = $userDetails->delete();
        if ($deleteUser) {
            return $this->successResponseArr(self::module . __('messages.success.delete'));
        }
    }
}
