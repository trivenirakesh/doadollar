<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $saveArr = [
            [
                'name' => 'Super Admin',
                'status' => 1,
            ],
            [
                'name' => 'Manager',
                'status' => 1,
            ],
            [
                'name' => 'User',
                'status' => 1,
            ]
        ];
        Role::insert($saveArr);

    }
}
