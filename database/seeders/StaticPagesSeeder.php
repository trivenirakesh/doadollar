<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StaticPage;
use App\Helpers\CommonHelper;

class StaticPagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        StaticPage::create([
            'title' => 'about_us',
            'content' => 'about_us',
            'created_at' => CommonHelper::getUTCDateTime(date('Y-m-d H:i:s')),
        ]);
        StaticPage::create([
            'title' => 'privacy_policy',
            'content' => 'privacy_policy',
            'created_at' => CommonHelper::getUTCDateTime(date('Y-m-d H:i:s')),
        ]);
        StaticPage::create([
            'title' => 'terms_and_condition',
            'content' => 'terms_and_condition',
            'created_at' => CommonHelper::getUTCDateTime(date('Y-m-d H:i:s')),
        ]);
    }
}
