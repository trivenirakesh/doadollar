<?php

namespace App\Services\V1;

use Illuminate\Http\Request;
use App\Models\UploadType;
use App\Traits\CommonTrait;
use App\Http\Resources\V1\UploadTypesResources;
use App\Helpers\CommonHelper;
use Illuminate\Support\Facades\Cache;

class UploadTypeService
{
    use CommonTrait;
    const module = 'Upload type';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $expiry = CommonHelper::getConfigValue('cache_expiry');
        $data =  (Cache::remember('uploadTypes', $expiry, function () {
            return UploadTypesResources::collection(UploadType::latest('id')->get());
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
        // save details 
        $uploadType = new UploadType();
        // remove blank spaces from string 
        $uploadTypeName = ucfirst(strtolower(str_replace(' ', '', $request->name)));
        $uploadType->name = $uploadTypeName;
        $uploadType->type = $request->type;
        $uploadType->created_by = auth()->user()->id;
        $uploadType->created_ip = CommonHelper::getUserIp();
        $uploadType->save();
        $lastId = $uploadType->id;
        $getUploadTypeDetails = new UploadTypesResources($uploadType);
        return $this->successResponseArr(self::module . __('messages.success.create'), $getUploadTypeDetails);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $getUploadTypeData = UploadType::where('id', $id)->first();
        if ($getUploadTypeData == null) {
            return $this->errorResponseArr(self::module . __('messages.validation.not_found'));
        }
        $getUploadTypeData = new UploadTypesResources($getUploadTypeData);
        return $this->successResponseArr(self::module . __('messages.success.details'), $getUploadTypeData);
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

        // update details 
        $uploadType = UploadType::where('id', $id)->first();
        if ($uploadType == null) {
            return $this->errorResponseArr(self::module . __('messages.validation.not_found'));
        }
        if($request->has('name')){
            $uploadTypeName = preg_replace('/\s+/', ' ', ucfirst(strtolower($request->name)));
            $uploadType->name = $uploadTypeName;
        }
        if($request->has('type')){
            $uploadType->type = $request->type;
        }
        if($request->has('status')){
            $uploadType->status = $request->status;
        }
        $uploadType->updated_by = auth()->user()->id;
        $uploadType->updated_ip = CommonHelper::getUserIp();
        $uploadType->update();
        $getUploadTypeDetails = new UploadTypesResources($uploadType);
        return $this->successResponseArr(self::module.__('messages.success.update'),$getUploadTypeDetails);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $uploadType =  UploadType::where('id', $id)->first();
        if ($uploadType == null) {
            return $this->errorResponseArr(self::module . __('messages.validation.not_found'));
        }
        // Delete entity
        $uploadType->deleted_by = auth()->user()->id;
        $uploadType->deleted_ip = CommonHelper::getUserIp();
        $uploadType->update();
        $deleteUploadType = $uploadType->delete();
        if ($deleteUploadType) {
            return $this->successResponseArr(self::module . __('messages.success.delete'));
        }
    }
}