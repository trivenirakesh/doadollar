<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\UploadTypeCreateUpdateRequest;
use App\Services\V1\UploadTypeService;

class UploadTypesController extends Controller
{

    private $uploadType;

    public function __construct(UploadTypeService $uploadType)
    {
        $this->uploadType = $uploadType;
    }

    public function index()
    {
        $uploadTypes =  $this->uploadType->index() ?? [];
        if (!$uploadTypes['status']) {
            return response()->json($uploadTypes, 401);
        }
        return response()->json($uploadTypes, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UploadTypeCreateUpdateRequest $request)
    {
        $uploadType  = $this->uploadType->store($request);
        if (!$uploadType['status']) {
            return response()->json($uploadType, 401);
        }
        return response()->json($uploadType, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $uploadType = $this->uploadType->show($id);
        if (!$uploadType['status']) {
            return response()->json($uploadType, 401);
        }
        return response()->json($uploadType, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UploadTypeCreateUpdateRequest $request, $id)
    {
        $uploadType = $this->uploadType->update($request, $id);
        if (!$uploadType['status']) {
            return response()->json($uploadType, 401);
        }
        return response()->json($uploadType, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $uploadType = $this->uploadType->destroy($id);
        if (!$uploadType['status']) {
            return response()->json($uploadType, 401);
        }
        return response()->json($uploadType, 200);
    }
}
