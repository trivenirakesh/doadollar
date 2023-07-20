<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\StaticPage;
use App\Traits\CommonTrait;
use App\Http\Resources\V1\StaticPageResource;
use App\Helpers\CommonHelper;
use Illuminate\Support\Facades\Cache;

class StaticPageController extends Controller
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
        $getStaticPagesList =  StaticPageResource::collection(Cache::remember('staticPages',$expiry,function(){
            return StaticPage::latest('id')->get();
        }));
        return $this->successResponse($getStaticPagesList, self::module.__('messages.success.list'), 200);
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
        $checkPageData = $this->getStaticPageDetails($request->title,1);
        
        // Validation section
        $validateUser = Validator::make($request->all(),
            [
                'title' => 'required',
                'content' => 'required',
            ],
            [
                'title.required' => __('messages.validation.title'),
                'content.required' => __('messages.validation.content'),
            ]
        );

        if ($validateUser->fails()) {
            return $this->errorResponse($validateUser->errors(), 401);
        }

        // get logged in user details 
        $getAdminDetails = auth('sanctum')->user();

        // Save entity section
        $updatePage = $checkPageData;
        $updatePage->title = $request->title;
        $updatePage->content = $request->content;
        if (!empty($getAdminDetails)) {
            $updatePage->updated_by = $getAdminDetails->id;
            $updatePage->updated_ip = CommonHelper::getUserIp();
        }
        $updatePage->update();
        $getPageData = $this->getStaticPageDetails($request->title,0);
        $getPageDetails = StaticPageResource::collection($getPageData);
        return $this->successResponse($getPageDetails, self::module.__('messages.success.update'), 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $getRoleDetails = $this->getStaticPageDetails($id,2);
        return $this->successResponse(StaticPageResource::collection($getRoleDetails), self::module.__('messages.success.details'), 200);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getStaticPageDetails($title,$type){
        if($type == 2){
            $type = 0;
            $getPagesData = StaticPage::where('id',$title);
        }else{
            $getPagesData = StaticPage::where('title',$title);
        }
        if($type == 1){
            $getPagesData = $getPagesData->first();
            if(!empty($getPagesData)){
                return $getPagesData;
            }else{
                throw new \ErrorException(self::module.__('messages.validation.not_found'));
            }
        }else{
            $getPagesData = $getPagesData->get();
            if(count($getPagesData) > 0){
                return $getPagesData;
            }else{
                throw new \ErrorException(self::module.__('messages.validation.not_found'));
            }
        }
        
    }
}
