<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\CampaignCategoryCreateUpdateRequest;
use App\Models\CampaignCategory;
use App\Services\V1\CampaignCategoryService;
use Illuminate\Http\Request;
use DataTables;

class CampaignCategoryController extends Controller
{
    protected $campaignCategoryService;

    public function __construct(CampaignCategoryService $campaignCategoryService)
    {
        $this->campaignCategoryService = $campaignCategoryService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $baseurl = route('admin.campaign-category.index');
            $data = CampaignCategory::with(['entitymst' => function ($query) {
                $query->select('id', 'first_name', 'last_name');
            }]);
            if ($request->order == null) {
                $data->orderBy('created_at', 'desc');
            }
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function ($row) use ($baseurl) {
                    $url = "<div class='actions-a' data-id='" . $row->id . "' data-url='" . $baseurl . "'>
                <a class='btn-circle theme_primary_btn module_view_record' title='View'><i class='fa fa-eye'></i></a>
                <a class='btn-circle theme_primary_btn module_edit_record' title='Edit'><i class='fa fa-edit'></i></a>
                <a class='btn-circle btn-danger module_delete_record' title='Delete'><i class='fa fa-trash-alt'></i></a>
                </div>";
                    return $url;
                })

                ->addColumn('image', function ($row) {
                    $image = '<img src="' . $row->image . '" class="img-fluid img-radius" width="40px" height="40px">';
                    return $image;
                })
                ->addColumn('status', function ($row) {
                    $statusText = $row->status == 1 ? "Active" : "Inactive";
                    $statusclass = $row->status == 1 ? "badge-primary" : " badge-danger";
                    $status = "<span class='text-md badge badge-pill $statusclass'>$statusText</span>";
                    return $status;
                })
                ->rawColumns(['actions', 'image', 'status'])
                ->make(true);
        }
        $title =  'Campaign Category';
        return view('admin.campaign-category.index', compact('title'));
    }

    /**
     * Store a newly created or update resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CampaignCategoryCreateUpdateRequest $request)
    {
        if (isset($request->id) && $request->id > 0) { //update data
            $campaignCategory = $this->campaignCategoryService->update($request, $request->id);
        } else { //add data
            $campaignCategory  = $this->campaignCategoryService->store($request);
        }
        if (!$campaignCategory['status']) {
            return response()->json($campaignCategory, 401);
        }
        return response()->json($campaignCategory, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $campaignCategory = $this->campaignCategoryService->show($id);
        if (!$campaignCategory['status']) {
            return response()->json($campaignCategory, 401);
        }
        return response()->json($campaignCategory, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $campaignCategory = $this->campaignCategoryService->destroy($id);
        if (!$campaignCategory['status']) {
            return response()->json($campaignCategory, 401);
        }
        return response()->json($campaignCategory, 200);
    }
}
