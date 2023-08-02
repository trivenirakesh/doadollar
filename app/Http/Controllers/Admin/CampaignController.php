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
            }])
                ->withSum('donation', 'donation_amount');
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
                ->addColumn('donation_target', function ($row) {
                    $donation_progress = empty($row->donation_sum_donation_amount) ? 0 : number_format((($row->donation_sum_donation_amount / $row->donation_target) * 100), 2);
                    return '<div><div class="progress rounded-pill">
                    <div class="theme_primary_btn  progress-bar" style="width: ' . $donation_progress . '%;" role="progressbar" aria-valuenow="' . $donation_progress . '" aria-valuemin="0" aria-valuemax="100"></div>
                  </div>
                  <div class="w-100 d-flex justify-content-between align-items-center"><span>$' . $row->donation_target . '</span><span>' . $donation_progress . '%</span></div>
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
                ->addColumn('created_by', function ($row) {
                    return $row->entitymst->first_name . ' ' . $row->entitymst->last_name;
                })
                ->addColumn('campaign_status_text', function ($row) {
                    $statusText = Campaign::CAMPAIGNSTATUSARR[$row->campaign_status] ?? -'';
                    $statusClass = Campaign::CAMPAIGNSTATUSCLASSARR[$row->campaign_status] ?? -'';
                    return '<div class="d-flex justify-content-center align-items-center"><span class="' . $statusClass . ' h3 px-2">&#9679</span>' . $statusText . '</div>';
                })
                ->rawColumns(['action_edit', 'action_delete', 'image', 'campaign_status_text', 'unique_code', 'donation_target', 'created_by'])
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
