<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\PaymentGatewayCreateUpdateRequest;
use App\Services\V1\PaymentGatewaySettingService;

class PaymentGatewaySettingController extends Controller
{

    private $paymentGatewaySettingService;

    public function __construct(PaymentGatewaySettingService $paymentGatewaySettingService)
    {
        $this->paymentGatewaySettingService = $paymentGatewaySettingService;
    }

    public function index()
    {
        $paymentGatewaySettings =  $this->paymentGatewaySettingService->index() ?? [];
        if (!$paymentGatewaySettings['status']) {
            return response()->json($paymentGatewaySettings, 401);
        }
        return response()->json($paymentGatewaySettings, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PaymentGatewayCreateUpdateRequest $request)
    {
        $paymentGateway  = $this->paymentGatewaySettingService->store($request);
        if (!$paymentGateway['status']) {
            return response()->json($paymentGateway, 401);
        }
        return response()->json($paymentGateway, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $paymentGateway = $this->paymentGatewaySettingService->show($id);
        if (!$paymentGateway['status']) {
            return response()->json($paymentGateway, 401);
        }
        return response()->json($paymentGateway, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PaymentGatewayCreateUpdateRequest $request, $id)
    {
        $paymentGateway = $this->paymentGatewaySettingService->update($request, $id);
        if (!$paymentGateway['status']) {
            return response()->json($paymentGateway, 401);
        }
        return response()->json($paymentGateway, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $paymentGateway = $this->paymentGatewaySettingService->destroy($id);
        if (!$paymentGateway['status']) {
            return response()->json($paymentGateway, 401);
        }
        return response()->json($paymentGateway, 200);
    }
}
