<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Entitymst;
use Illuminate\Support\Facades\Hash;

class EntitymstSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Entitymst::create([
            'first_name' => 'Super Admin',
            'last_name' => '',
            'email' => 'doadollar@admin.com',
            'mobile' => '',
            'password' => Hash::make('123456789'),
            'entity_type' => 0,
            'status' => 1,
        ]);
    }
}
