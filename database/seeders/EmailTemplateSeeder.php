<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EmailTemplate;
use App\Helpers\CommonHelper;

class EmailTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EmailTemplate::create([
            'title' => 'Forgot password',
            'subject' => 'Forgot password',
            'message' => 'Forgot password',
            'status' => 1,
            'created_at' => CommonHelper::getUTCDateTime(date('Y-m-d H:i:s')),
        ]);
    }
}
