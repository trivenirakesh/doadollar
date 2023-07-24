<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\UsersCreateUpdateRequest;
use App\Services\V1\UserService;

class UsersController extends Controller
{
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
    public function changePassword(Request $request)
    {

        // Validation section
        $rules = [];
        $messages = [];
        $rules = [
            'id' => 'required',
            'old_password' => 'required',
            'new_password' => 'required'
        ];

        $messages = [
            'id.required' => __('messages.validation.id'),
            'old_password.required' => __('messages.validation.old_password'),
            'new_password.required' => __('messages.validation.new_password')
        ];

        $validateUser = Validator::make($request->all(), $rules, $messages);

        if ($validateUser->fails()) {
            return $this->errorResponse($validateUser->errors(), 400);
        }

        // check user exist or not 
        $userId = $request->id;
        $checkUser = $this->getUserDetails($userId, 1);

        //Match The Old Password
        $getAdminDetails = auth('sanctum')->user();
        if (!Hash::check($request->old_password, $getAdminDetails->password)) {
            return $this->errorResponse(__('messages.success.old_password_wrong'), 400);
        }

        $password = $request->new_password;
        $uppercase = preg_match('@[A-Z]@', $password);
        $lowercase = preg_match('@[a-z]@', $password);
        $number    = preg_match('@[0-9]@', $password);
        $specialChars = preg_match('@[^\w]@', $password);
        if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
            $errorMessage = __('messages.validation.strong_password');
            return $this->errorResponse($errorMessage, 400);
        }

        $updateUserPassword = $checkUser;
        $updateUserPassword->password = Hash::make($request->new_password);
        $updateUserPassword->update();
        if ($updateUserPassword) {
            return $this->successResponse([], __('messages.success.password_reset'), 200);
        }
    }
}
