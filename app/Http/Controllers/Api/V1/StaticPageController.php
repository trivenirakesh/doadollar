<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StaticPageCreateUpdateRequest;
use App\Services\V1\StaticPageService;

class StaticPageController extends Controller
{
    private $staticPage;

    public function __construct(StaticPageService $staticPage)
    {
        $this->staticPage = $staticPage;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $staticPages =  $this->staticPage->index() ?? [];
        if (!$staticPages['status']) {
            return response()->json($staticPages, 401);
        }
        return response()->json($staticPages, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StaticPageCreateUpdateRequest $request)
    {
        $staticPages  = $this->staticPage->store($request);
        if (!$staticPages['status']) {
            return response()->json($staticPages, 401);
        }
        return response()->json($staticPages, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $staticPage = $this->staticPage->show($id);
        if (!$staticPage['status']) {
            return response()->json($staticPage, 401);
        }
        return response()->json($staticPage, 200);
    }
}
