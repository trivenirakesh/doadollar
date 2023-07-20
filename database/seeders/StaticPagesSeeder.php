<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StaticPage;

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
        ]);
        StaticPage::create([
            'title' => 'privacy_policy',
            'content' => 'privacy_policy',
        ]);
        StaticPage::create([
            'title' => 'terms_and_condition',
            'content' => 'terms_and_condition',
        ]);
    }
}
