<?php

namespace App\Services\V1;

use Illuminate\Http\Request;
use App\Models\StaticPage;
use App\Traits\CommonTrait;
use App\Http\Resources\V1\StaticPageResource;
use App\Helpers\CommonHelper;
use Illuminate\Support\Facades\Cache;

class StaticPageService
{
    use CommonTrait;
    const module = 'Page';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $expiry = CommonHelper::getConfigValue('cache_expiry');
        $data =  (Cache::remember('staticPages', $expiry, function () {
            return StaticPageResource::collection(StaticPage::latest('id')->get());
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
        if (!empty($request->title)) {
            $PageData = StaticPage::where('title', $request->title)->first();
            if ($PageData == null) {
                return $this->errorResponseArr(self::module . __('messages.validation.not_found'));
            }
        }

        // Update page section
        $PageData->title = $request->title;
        $PageData->content = $request->content;
        $PageData->updated_by = auth()->user()->id;
        $PageData->updated_ip = CommonHelper::getUserIp();
        $PageData->update();
        $getPageDetails = new StaticPageResource($PageData);
        return $this->successResponseArr(self::module . __('messages.success.update'), $getPageDetails);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $getPageData = StaticPage::where('id', $id)->orWhere('title', $id)->first();
        if ($getPageData == null) {
            return $this->errorResponseArr(self::module . __('messages.validation.not_found'));
        }
        $getPageData = new StaticPageResource($getPageData);
        return $this->successResponseArr(self::module . __('messages.success.details'), $getPageData);
    }
}
