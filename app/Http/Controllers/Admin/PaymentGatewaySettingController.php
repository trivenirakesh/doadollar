<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CommonHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\PaymentGatewayCreateUpdateRequest;
use App\Models\PaymentGatewaySetting;
use App\Services\V1\PaymentGatewaySettingService;
use App\Traits\CommonTrait;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PaymentGatewaySettingController extends Controller
{
    use CommonTrait;
    protected $paymentGatewayService;

    public function __construct(PaymentGatewaySettingService $paymentGatewayService)
    {
        $this->paymentGatewayService = $paymentGatewayService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $baseurl = route('admin.payment-gateway.index');
            $data = PaymentGatewaySetting::latest()->get();
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
                ->editColumn('api_key', function($row){
                    return CommonHelper::shortString($row->api_key,30);
                })
                ->editColumn('secret_key', function($row){
                    return CommonHelper::shortString($row->secret_key,30);
                })
                ->addColumn('status', function ($row) {
                    return $this->statusHtml($row);
                })
                ->rawColumns(['action_edit', 'action_delete', 'image','api_key','secret_key', 'status'])
                ->make(true);
        }
        $title =  'Payment Gateway Setting';
        return view('admin.payment-gateway.index', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PaymentGatewayCreateUpdateRequest $request)
    {
        if (isset($request->id) && $request->id > 0) { //update data
            $paymentGatewayCreateUpdate = $this->paymentGatewayService->update($request, $request->id);
        } else { //add data
            $paymentGatewayCreateUpdate  = $this->paymentGatewayService->store($request);
        }
        if (!$paymentGatewayCreateUpdate['status']) {
            return response()->json($paymentGatewayCreateUpdate, 401);
        }
        return response()->json($paymentGatewayCreateUpdate, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $paymentGatewayDetail = $this->paymentGatewayService->show($id);
        if (!$paymentGatewayDetail['status']) {
            return response()->json($paymentGatewayDetail, 401);
        }
        return response()->json($paymentGatewayDetail, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $paymentGatewayDelete = $this->paymentGatewayService->destroy($id);
        if (!$paymentGatewayDelete['status']) {
            return response()->json($paymentGatewayDelete, 401);
        }
        return response()->json($paymentGatewayDelete, 200);
    }
}
