<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\SocialPlatformCreateUpdateRequest;
use App\Models\Entitymst;
use App\Models\Role;
use App\Models\SocialPlatformSetting;
use App\Services\V1\SocialPlatformSettingService;
use App\Traits\CommonTrait;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SocialPlatformSettingController extends Controller
{
    use CommonTrait;
    protected $socialPlatformSettingService;

    public function __construct(SocialPlatformSettingService $socialPlatformSettingService)
    {
        $this->socialPlatformSettingService = $socialPlatformSettingService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $baseurl = route('admin.social-media-settings.index');
            $data = SocialPlatformSetting::with(['entitymst' => function ($query) {
                $query->select('id', 'first_name', 'last_name');
            }]);
            if ($request->order == null) {
                $data->orderBy('created_at', 'desc');
            }
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action_edit', function ($row) use ($baseurl) {
                    return $this->actionHtml($baseurl, $row->id, false);
                })
                ->addColumn('action_delete', function ($row) use ($baseurl) {
                    return $this->actionHtml($baseurl, $row->id, true);
                })
                ->addColumn('image', function ($row) {
                    $image = '<img src="' . $row->image . '" class="img-fluid img-radius" width="40px" height="40px">';
                    return $image;
                })
                ->addColumn('status_text', function ($row) {
                    return $this->statusHtml($row);
                })
                ->rawColumns(['action_edit', 'image', 'action_delete', 'status_text'])
                ->make(true);
        }
        $title =  'Social Media Settings';
        $entityTypes = Entitymst::ENTITYTYPES;
        return view('admin.social-media-settings.index', compact('title', 'entityTypes'));
    }

    /**
     * Store a newly created or update resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SocialPlatformCreateUpdateRequest $request)
    {

        if (isset($request->id) && $request->id > 0) { //update data
            $socialPlatformSetting = $this->socialPlatformSettingService->update($request, $request->id);
        } else { //add data
            $socialPlatformSetting  = $this->socialPlatformSettingService->store($request);
        }
        if (!$socialPlatformSetting['status']) {
            return response()->json($socialPlatformSetting, 401);
        }
        return response()->json($socialPlatformSetting, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $socialPlatformSetting = $this->socialPlatformSettingService->show($id);
        if (!$socialPlatformSetting['status']) {
            return response()->json($socialPlatformSetting, 401);
        }
        return response()->json($socialPlatformSetting, 200);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $socialPlatformSetting = $this->socialPlatformSettingService->destroy($id);
        if (!$socialPlatformSetting['status']) {
            return response()->json($socialPlatformSetting, 401);
        }
        return response()->json($socialPlatformSetting, 200);
    }
}
