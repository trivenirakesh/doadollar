<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentGatewaySetting;

class PaymentGatewaySettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PaymentGatewaySetting::create([
            'name' => 'Paypal',
            'api_key' => 'AUkIARVW4cIZ_NyAEFby7ugloXS-RsEYajRwwlqT5TWJjDlBVUB5fbjMTgrTylI-BLHABz8xbTGkxSQs',
            'secret_key' => 'EK5Y55N_hHYavWH9WviWZmzmWB9siP22C5wg5Qaj7XLtu_QDQPP5S7IbB3CmK-8FGZtUBTKnFVv_UDuE',
            'status' => 1,
        ]);
    }
}
