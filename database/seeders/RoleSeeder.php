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
        Role::create([
            'name' => 'super_admin',
            'status' => 1,
        ]);

        Role::create([
            'name' => 'manager',
            'status' => 1,
        ]);

        Role::create([
            'name' => 'user',
            'status' => 1,
        ]);
    }
}
