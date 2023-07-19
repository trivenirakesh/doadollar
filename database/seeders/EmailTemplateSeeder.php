<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EmailTemplate;

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
        ]);
    }
}
