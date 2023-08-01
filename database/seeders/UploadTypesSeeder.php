<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UploadType;

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
        ]);
    }
}
