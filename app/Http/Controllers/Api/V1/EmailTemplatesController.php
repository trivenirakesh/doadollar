<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\EmailTemplate;
use App\Traits\CommonTrait;
use App\Http\Resources\V1\EmailTemplateResource;
use App\Helpers\CommonHelper;


class EmailTemplatesController extends Controller
{
    use CommonTrait;
    const module = 'Email template';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $getEmailTemplateDetails = new EmailTemplate();
        $getEmailTemplateDetails = $getEmailTemplateDetails->orderBy('id','DESC')->paginate(10);
        return EmailTemplateResource::collection($getEmailTemplateDetails); 
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
        $validateUser = Validator::make($request->all(),
            [
                'title' => 'required',
                'subject' => 'required',
                'message' => 'required',
            ],
            [
                'title.required' => __('messages.validation.title'),
                'subject.required' => __('messages.validation.subject'),
                'message.required' => __('messages.validation.message'),
            ]
        );

        if ($validateUser->fails()) {
            return $this->errorResponse($validateUser->errors(), 401);
        }

        // get logged in user details 
        $getAdminDetails = auth('sanctum')->user();

        // Save entity section
        $emailTemplateTitle = preg_replace('/\s+/', ' ', ucwords(strtolower($request->title)));
        $emailTemplate = new EmailTemplate();
        $emailTemplate->title = $emailTemplateTitle;
        $emailTemplate->subject = $request->subject;
        $emailTemplate->message = $request->message;
        $emailTemplate->created_at = CommonHelper::getUTCDateTime(date('Y-m-d H:i:s'));
        if (!empty($getAdminDetails)) {
            $emailTemplate->created_by = $getAdminDetails->id;
            $emailTemplate->created_ip = CommonHelper::getUserIp();
        }
        $emailTemplate->save();
        $lastId = $emailTemplate->id;
        $getRoleData = $this->getEmailTemplateDetails($lastId,0);
        $getEmailTemplateDetails = EmailTemplateResource::collection($getRoleData);
        return $this->successResponse($getEmailTemplateDetails, self::module.__('messages.success.create'), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $getEmailTemplateDetails = $this->getEmailTemplateDetails($id,0);
        return $this->successResponse(EmailTemplateResource::collection($getEmailTemplateDetails), self::module.__('messages.success.details'), 200);
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
        $checkEmailTemplate = $this->getEmailTemplateDetails($id,1);

        // Validation section
        $rules = [];
        $messages = [];
        if($request->has('title')){
            $rules['title'] = 'required';
            $messages['title.required'] = __('messages.validation.title');
        }
        if($request->has('subject')){
            $rules['subject'] = 'required';
            $messages['subject.required'] = __('messages.validation.subject');
        }
        if($request->has('message')){
            $rules['message'] = 'required';
            $messages['message.required'] = __('messages.validation.message');
        }
        if($request->has('status')){
            $rules['status'] = 'required|numeric|lte:1';
            $messages['status.required'] = __('messages.validation.status');
            $messages['status.numeric'] = __('messages.validation.status_numeric');
            $messages['status.lte'] = __('messages.validation.status_lte');
        }
        $validateUser = Validator::make($request->all(),$rules,$messages);

        if ($validateUser->fails()) {
            return $this->errorResponse($validateUser->errors(), 401);
        }

        // get logged in user details 
        $getAdminDetails = auth('sanctum')->user();

        // Save entity section
        $emailTemplate = $checkEmailTemplate;
        if($request->has('title')){
            $emailTemplateName = preg_replace('/\s+/', ' ', ucwords(strtolower($request->title)));
            $emailTemplate->title = $emailTemplateName;
        }
        if($request->has('subject')){
            $emailTemplate->subject = $request->subject;
        }
        if($request->has('message')){
            $emailTemplate->message = $request->message;
        }
        if($request->has('status')){
            $emailTemplate->status = $request->status;
        }
        $emailTemplate->updated_at = CommonHelper::getUTCDateTime(date('Y-m-d H:i:s'));
        if (!empty($getAdminDetails) && !empty($getAdminDetails->id)) {
            $emailTemplate->updated_by = $getAdminDetails->id;
            $emailTemplate->updated_ip = CommonHelper::getUserIp();
        }
        $emailTemplate->update();
        $getTemplateData = $this->getEmailTemplateDetails($id,0);
        $getEmailTemplateDetails = EmailTemplateResource::collection($getTemplateData);
        return $this->successResponse($getEmailTemplateDetails, self::module.__('messages.success.update'), 200);
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
        $checkEmailTemplate = $this->getEmailTemplateDetails($id,1);

        // get logged in user details 
        $getAdminDetails = auth('sanctum')->user();

        // Delete entity
        $checkEmailTemplateData = $checkEmailTemplate;
        if (!empty($checkEmailTemplateData)) {
            // $checkEmailTemplateData->deleted_at = CommonHelper::getUTCDateTime(date('Y-m-d H:i:s'));
            $checkEmailTemplateData->deleted_by = $getAdminDetails->id;
            $checkEmailTemplateData->deleted_ip = CommonHelper::getUserIp();
            $checkEmailTemplateData->update();
            $deleteRole = EmailTemplate::find($id)->delete();
            if ($deleteRole) {
                return $this->successResponse([], self::module.__('messages.success.delete'), 200);
            }
        }
    }

    public function getEmailTemplateDetails($id,$type){
        $getEmailTemplateData = EmailTemplate::where('id',$id);
        if($type == 1){
            $getEmailTemplateData = $getEmailTemplateData->first();
            if(!empty($getEmailTemplateData)){
                return $getEmailTemplateData;
            }else{
                throw new \ErrorException(self::module.__('messages.validation.not_found'));
            }
        }else{
            $getEmailTemplateData = $getEmailTemplateData->get();
            if(count($getEmailTemplateData) > 0){
                return $getEmailTemplateData;
            }else{
                throw new \ErrorException(self::module.__('messages.validation.not_found'));
            }
        }   
    }
}
