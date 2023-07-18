<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SocialPlatformSetting;
use App\Helpers\CommonHelper;

class SocialPlatformSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SocialPlatformSetting::create([
            'name' => 'Facebook',
            'api_key' => 'AUkIARVW4cIZ_NyAEFby7ugloXS-RsEYajRwwlqT5TWJjDlBVUB5fbjMTgrTylI-BLHABz8xbTGkxSQs',
            'secret_key' => 'EK5Y55N_hHYavWH9WviWZmzmWB9siP22C5wg5Qaj7XLtu_QDQPP5S7IbB3CmK-8FGZtUBTKnFVv_UDuE',
            'status' => 1,
            'created_at' => CommonHelper::getUTCDateTime(date('Y-m-d H:i:s')),
        ]);
    }
}
