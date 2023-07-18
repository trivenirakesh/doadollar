<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CampaignCategory;
use App\Helpers\CommonHelper;
class CampaignCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CampaignCategory::create([
            'name' => 'Medical',
            'description' => 'Medical',
            'status' => 1,
            'created_at' => CommonHelper::getUTCDateTime(date('Y-m-d H:i:s')),
        ]);
    }
}
