<?php

namespace App\Observers;

use App\Models\PaymentGatewaySetting;
use Illuminate\Support\Facades\Cache;

class PaymentGatewaySettingObserver
{
    /**
     * Handle the PaymentGatewaySetting "created" event.
     *
     * @param  \App\Models\PaymentGatewaySetting  $paymentGatewaySetting
     * @return void
     */
    public function created(PaymentGatewaySetting $paymentGatewaySetting)
    {
        Cache::forget('paymentGatewaySetting');
    }

    /**
     * Handle the PaymentGatewaySetting "updated" event.
     *
     * @param  \App\Models\PaymentGatewaySetting  $paymentGatewaySetting
     * @return void
     */
    public function updated(PaymentGatewaySetting $paymentGatewaySetting)
    {
        Cache::forget('paymentGatewaySetting');
    }

    /**
     * Handle the PaymentGatewaySetting "deleted" event.
     *
     * @param  \App\Models\PaymentGatewaySetting  $paymentGatewaySetting
     * @return void
     */
    public function deleted(PaymentGatewaySetting $paymentGatewaySetting)
    {
        Cache::forget('paymentGatewaySetting');
    }

    /**
     * Handle the PaymentGatewaySetting "restored" event.
     *
     * @param  \App\Models\PaymentGatewaySetting  $paymentGatewaySetting
     * @return void
     */
    public function restored(PaymentGatewaySetting $paymentGatewaySetting)
    {
        //
    }

    /**
     * Handle the PaymentGatewaySetting "force deleted" event.
     *
     * @param  \App\Models\PaymentGatewaySetting  $paymentGatewaySetting
     * @return void
     */
    public function forceDeleted(PaymentGatewaySetting $paymentGatewaySetting)
    {
        //
    }
}
