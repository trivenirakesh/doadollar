<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\CampaignCategoryCreateUpdateRequest;
use App\Models\CampaignCategory;
use App\Services\V1\CampaignCategoryService;
use App\Traits\CommonTrait;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CampaignCategoryController extends Controller
{
    use CommonTrait;
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
                ->addColumn('status', function ($row) {
                    return $this->statusHtml($row);
                })
                ->rawColumns(['action_edit', 'action_delete', 'image', 'status'])
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
