<?php

namespace App\Services\V1;

use Illuminate\Http\Request;
use App\Models\EmailTemplate;
use App\Traits\CommonTrait;
use App\Http\Resources\V1\EmailTemplateResource;
use App\Helpers\CommonHelper;
use Illuminate\Support\Facades\Cache;

class EmailTemplateService
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
        $expiry = CommonHelper::getConfigValue('cache_expiry');
        $data =  (Cache::remember('emailTemplates', $expiry, function () {
            return EmailTemplateResource::collection(EmailTemplate::latest('id')->get());
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
        $emailTemplate = new EmailTemplate();
        // remove blank spaces from string 
        $emailTemplate->title = $request->title;
        $emailTemplate->subject = $request->subject;
        $emailTemplate->message = $request->message;
        $emailTemplate->created_by = auth()->user()->id;
        $emailTemplate->created_ip = CommonHelper::getUserIp();
        $emailTemplate->save();
        $getUploadTypeDetails = new EmailTemplateResource($emailTemplate);
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
        $getEmailTemplateData = EmailTemplate::where('id', $id)->first();
        if ($getEmailTemplateData == null) {
            return $this->errorResponseArr(self::module . __('messages.validation.not_found'));
        }
        $getEmailTemplateData = new EmailTemplateResource($getEmailTemplateData);
        return $this->successResponseArr(self::module . __('messages.success.details'), $getEmailTemplateData);
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
        $emailTemplate = EmailTemplate::where('id', $id)->first();
        if ($emailTemplate == null) {
            return $this->errorResponseArr(self::module . __('messages.validation.not_found'));
        }
        $emailTemplate->title = $request->title;
        $emailTemplate->subject = $request->subject;
        $emailTemplate->message = $request->message;
        if ($request->has('status')) {
            $emailTemplate->status = $request->status;
        }
        $emailTemplate->updated_by = auth()->user()->id;
        $emailTemplate->updated_ip = CommonHelper::getUserIp();
        $emailTemplate->update();
        $getEmailTemplateDetails = new EmailTemplateResource($emailTemplate);
        return $this->successResponseArr(self::module . __('messages.success.update'), $getEmailTemplateDetails);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $emailTemplate =  EmailTemplate::where('id', $id)->first();
        if ($emailTemplate == null) {
            return $this->errorResponseArr(self::module . __('messages.validation.not_found'));
        }
        // Delete email template
        $emailTemplate->deleted_by = auth()->user()->id;
        $emailTemplate->deleted_ip = CommonHelper::getUserIp();
        $emailTemplate->update();
        $deleteEmailTemplate = $emailTemplate->delete();
        if ($deleteEmailTemplate) {
            return $this->successResponseArr(self::module . __('messages.success.delete'));
        }
    }
}
