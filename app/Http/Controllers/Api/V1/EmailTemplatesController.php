<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\EmailTemplateCreateUpdateRequest;
use App\Services\V1\EmailTemplateService;

class EmailTemplatesController extends Controller
{

    private $emailTemplate;

    public function __construct(EmailTemplateService $emailTemplate)
    {
        $this->emailTemplate = $emailTemplate;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $emailTemplates =  $this->emailTemplate->index() ?? [];
        if (!$emailTemplates['status']) {
            return response()->json($emailTemplates, 401);
        }
        return response()->json($emailTemplates, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EmailTemplateCreateUpdateRequest $request)
    {
        $emailTemplate  = $this->emailTemplate->store($request);
        if (!$emailTemplate['status']) {
            return response()->json($emailTemplate, 401);
        }
        return response()->json($emailTemplate, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $emailTemplate = $this->emailTemplate->show($id);
        if (!$emailTemplate['status']) {
            return response()->json($emailTemplate, 401);
        }
        return response()->json($emailTemplate, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EmailTemplateCreateUpdateRequest $request, $id)
    {
        $emailTemplate = $this->emailTemplate->update($request, $id);
        if (!$emailTemplate['status']) {
            return response()->json($emailTemplate, 401);
        }
        return response()->json($emailTemplate, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $emailTemplate = $this->emailTemplate->destroy($id);
        if (!$emailTemplate['status']) {
            return response()->json($emailTemplate, 401);
        }
        return response()->json($emailTemplate, 200);
    }
}
