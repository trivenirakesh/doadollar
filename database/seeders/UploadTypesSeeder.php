<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UploadType;
use App\Helpers\CommonHelper;

class UploadTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UploadType::create([
            'name' => 'Image',
            'type' => 0,
            'status' => 1,
            'created_at' => CommonHelper::getUTCDateTime(date('Y-m-d H:i:s')),
        ]);
    }
}
