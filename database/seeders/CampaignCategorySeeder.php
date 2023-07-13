<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CampaignCategory;
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
            'status' => 1
        ]);
    }
}
