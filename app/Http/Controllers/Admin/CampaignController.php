<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\CampaignCreateUpdateRequest;
use App\Models\Campaign;
use App\Models\CampaignCategory;
use App\Services\V1\CampaignService;
use App\Traits\CommonTrait;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CampaignController extends Controller
{
    use CommonTrait;
    protected $campaignService;

    public function __construct(CampaignService $campaignService)
    {
        $this->campaignService = $campaignService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $baseurl = route('admin.campaigns.index');
            $data = Campaign::with(['entitymst' => function ($query) {
                $query->select('id', 'first_name', 'last_name');
            }]);
            if ($request->order == null) {
                $data->orderBy('created_at', 'desc');
            }
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action_edit', function ($row) use ($baseurl) {
                    $editUrl = "$baseurl/$row->id/edit";
                    $show = "$baseurl/$row->id";
                    $links = "<div class='actions-a'>
                    <a href='$editUrl' class='btn-circle theme_primary_btn module_edit_record' title='Edit'><i class='fa fa-pen'></i></a>
                    <a href='$show' class='btn-circle theme_primary_btn module_view_record' title='View'><i class='fa fa-eye'></i></a>
                    </div>";
                    return $links;
                })
                ->addColumn('action_delete', function ($row) use ($baseurl) {
                    return $this->actionHtml($baseurl, $row->id, true);
                })
                ->addColumn('donation_target', function ($row) use ($baseurl) {
                    return '<div><div class="progress rounded-pill">
                    <div class="theme_primary_btn  progress-bar w-75" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                  </div>
                  <div class="w-100 d-flex justify-content-between align-items-center"><span>' . $row->donation_target . '</span><span>30%</span></div>
                  </div>';
                })
                ->addColumn('unique_code', function ($row) use ($baseurl) {
                    $url = $baseurl . "/" . $row->unique_code;
                    return "<span style='cursor:pointer' data-content='$url' onclick='Copy(this);'>Copy url <i class='far fa-copy'></i></span>";
                })
                ->addColumn('image', function ($row) {
                    $image = '<img src="' . $row->image . '" class="img-fluid img-radius" width="40px" height="40px">';
                    return $image;
                })
                ->addColumn('status_text', function ($row) {
                    return $this->statusHtml($row);
                })
                ->rawColumns(['action_edit', 'action_delete', 'image', 'status_text', 'unique_code', 'donation_target'])
                ->make(true);
        }

        $title =  'Campaign';
        return view('admin.campaign.index', compact('title'));
    }

    /**
     * Show Create resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title =  'Campaign Create';
        $campaignCategories = CampaignCategory::select('id', 'name', 'image')->active()->orderBy('id', 'desc')->get();
        return view('admin.campaign.create', compact('title', 'campaignCategories'));
    }


    /**
     * Store a newly created or update resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CampaignCreateUpdateRequest $request)
    {
        $request->request->remove('image_description');
        $request->request->remove('title');
        $request->request->remove('url');
        $campaign  = $this->campaignService->storeData($request);
        if (!$campaign['status']) {
            return response()->json($campaign, 401);
        }
        return response()->json($campaign, 200);
    }

    /**
     * Store a newly created or update resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(CampaignCreateUpdateRequest $request, $id)
    {
        $request->request->remove('image_description');
        $request->request->remove('title');
        $request->request->remove('url');
        $campaign  = $this->campaignService->updateData($request, $id);
        if (!$campaign['status']) {
            return response()->json($campaign, 401);
        }
        return response()->json($campaign, 200);
    }

    /**
     * Show Edit resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $title =  'Campaign Edit';
        $campaign = $this->campaignService->show($id);
        if (!$campaign['status']) {
            abort(404, 'resource not found');
        }
        $campaign = $campaign['data'];
        $campaignCategories = CampaignCategory::select('id', 'name', 'image')->active()->orderBy('id', 'desc')->get();
        return view('admin.campaign.edit', compact('title', 'campaignCategories', 'campaign'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $campaign = $this->campaignService->show($id);
        if (!$campaign['status']) {
            abort(404, 'resource not found');
        }
        $campaign = $campaign['data'];
        $title =  $campaign->name;
        return view('admin.campaign.show', compact('title', 'campaign'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $campaignCategory = $this->campaignService->destroy($id);
        if (!$campaignCategory['status']) {
            return response()->json($campaignCategory, 401);
        }
        return response()->json($campaignCategory, 200);
    }
}
