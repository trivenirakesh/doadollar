<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\UploadType;
use App\Traits\CommonTrait;
use App\Http\Resources\V1\UploadTypesResources;
use App\Helpers\CommonHelper;
use Illuminate\Support\Facades\Cache;

class UploadTypesController extends Controller
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
        return UploadTypesResources::collection(Cache::remember('uploadTypes',60*60*24,function(){
            return UploadType::latest('id')->get();
        }));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validation section
        $validateUploadType = Validator::make($request->all(),
            [
                'name' => 'required',
                'type' => 'required|numeric|lte:1'
            ],
            [
                'name.required' => __('messages.validation.name'),
                'type.required' => __('messages.validation.type'),
                'type.numeric' => 'Type'.__('messages.validation.must_numeric'),
                'type.lte' => __('messages.validation.type_lte'),
            ]
        );

        if ($validateUploadType->fails()) {
            return $this->errorResponse($validateUploadType->errors(), 401);
        }

        // get logged in user details 
        $getAdminDetails = auth('sanctum')->user();

        // Save upload type section
        $uploadTypeName = preg_replace('/\s+/', ' ', ucwords(strtolower($request->name)));
        $uploadType = new UploadType();
        $uploadType->name = $uploadTypeName;
        $uploadType->type = $request->type;
        $uploadType->created_at = CommonHelper::getUTCDateTime(date('Y-m-d H:i:s'));
        if (!empty($getAdminDetails)) {
            $uploadType->created_by = $getAdminDetails->id;
            $uploadType->created_ip = CommonHelper::getUserIp();
        }
        $uploadType->save();
        $lastId = $uploadType->id;
        $getUploadTypeData = $this->getTypeDetails($lastId,0);
        $getTypeDetails = UploadTypesResources::collection($getUploadTypeData);
        return $this->successResponse($getTypeDetails, self::module.__('messages.success.create'), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $getTypeDetails = $this->getTypeDetails($id,0);
        return $this->successResponse(UploadTypesResources::collection($getTypeDetails), self::module.__('messages.success.details'), 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        // check role exist or not 
        $checkRole = $this->getTypeDetails($id,1);

        // Validation section
        $rules = [];
        $messages = [];
        if($request->has('name')){
            $rules['name'] = 'required';
            $messages['name.required'] = __('messages.validation.name');
        }
        if($request->has('type')){
            $rules['type'] = 'required|numeric|lte:1';
            $messages['type.required'] = __('messages.validation.type');
            $messages['type.numeric'] = 'Type'.__('messages.validation.must_numeric');
            $messages['type.lte'] = __('messages.validation.type_lte');
        }
        if($request->has('status')){
            $rules['status'] = 'required|numeric|lte:1';
            $messages['status.required'] = __('messages.validation.status');
            $messages['status.numeric'] = 'Status'.__('messages.validation.must_numeric');
            $messages['status.lte'] = __('messages.validation.status_lte');
        }
        $validateUploadType = Validator::make($request->all(),$rules,$messages);

        if ($validateUploadType->fails()) {
            return $this->errorResponse($validateUploadType->errors(), 401);
        }

        // get logged in user details 
        $getAdminDetails = auth('sanctum')->user();

        // Save upload type section
        $uploadType = $checkRole;
        if($request->has('name')){
            $uploadTypeName = preg_replace('/\s+/', ' ', ucwords(strtolower($request->name)));
            $uploadType->name = $uploadTypeName;
        }
        if($request->has('type')){
            $uploadType->type = $request->type;
        }
        if($request->has('status')){
            $uploadType->status = $request->status;
        }
        $uploadType->updated_at = CommonHelper::getUTCDateTime(date('Y-m-d H:i:s'));
        if (!empty($getAdminDetails) && !empty($getAdminDetails->id)) {
            $uploadType->updated_by = $getAdminDetails->id;
            $uploadType->updated_ip = CommonHelper::getUserIp();
        }
        $uploadType->update();
        $getUploadTypeData = $this->getTypeDetails($id,0);
        $getTypeDetails = UploadTypesResources::collection($getUploadTypeData);
        return $this->successResponse($getTypeDetails, self::module.__('messages.success.update'), 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // check role exist or not 
        $checkRole = $this->getTypeDetails($id,1);

        // get logged in user details 
        $getAdminDetails = auth('sanctum')->user();

        // Delete upload type
        $checkUploadTypeData = $checkRole;
        if (!empty($checkUploadTypeData)) {
            // $checkUploadTypeData->deleted_at = CommonHelper::getUTCDateTime(date('Y-m-d H:i:s'));
            $checkUploadTypeData->deleted_by = $getAdminDetails->id;
            $checkUploadTypeData->deleted_ip = CommonHelper::getUserIp();
            $checkUploadTypeData->update();
            $deleteRole = UploadType::find($id)->delete();
            if ($deleteRole) {
                return $this->successResponse([], self::module.__('messages.success.delete'), 200);
            }
        }
    }

    /**
     * Fetch the specified resource.
     *
     */
    public function getTypeDetails($id,$type){
        $getUploadTypeData = UploadType::where('id',$id);
        if($type == 1){
            $getUploadTypeData = $getUploadTypeData->first();
            if(!empty($getUploadTypeData)){
                return $getUploadTypeData;
            }else{
                throw new \ErrorException(self::module.__('messages.validation.not_found'));
            }
        }else{
            $getUploadTypeData = $getUploadTypeData->get();
            if(count($getUploadTypeData) > 0){
                return $getUploadTypeData;
            }else{
                throw new \ErrorException(self::module.__('messages.validation.not_found'));
            }
        }
        
    }
}
