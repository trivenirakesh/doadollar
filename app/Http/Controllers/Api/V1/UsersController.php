<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\ChangePasswordRequest;
use App\Http\Requests\V1\UsersCreateUpdateRequest;
use App\Models\Entitymst;
use App\Services\V1\UserService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Traits\CommonTrait;

class UsersController extends Controller
{
    use CommonTrait;
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usersList =  $this->userService->index() ?? [];
        if (!$usersList['status']) {
            return response()->json($usersList, 401);
        }
        return response()->json($usersList, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $getUser  = $this->userService->show($id);
        if (!$getUser['status']) {
            return response()->json($getUser, 401);
        }
        return response()->json($getUser, 200);
    }

    /**
     * Store a newly created entity in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UsersCreateUpdateRequest $request)
    {
        $userCreate  = $this->userService->store($request);
        if (!$userCreate['status']) {
            return response()->json($userCreate, 401);
        }
        return response()->json($userCreate, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UsersCreateUpdateRequest $request, $id)
    {

        $userUpdate = $this->userService->update($request, $id);
        if (!$userUpdate['status']) {
            return response()->json($userUpdate, 401);
        }
        return response()->json($userUpdate, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $userDelete = $this->userService->destroy($id);
        if (!$userDelete['status']) {
            return response()->json($userDelete, 401);
        }
        return response()->json($userDelete, 200);
    }

    /**
     * Changed password for entity.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function changePassword(ChangePasswordRequest $request)
    {
        $userId = Auth::user()->id;
        $user = Entitymst::where('id', $userId)->first();
        $validatedData['password'] = Hash::make($request->password);
        $user->update($validatedData);
        return $this->successResponse([], __('messages.success.password_reset'), 200);
    }
}
