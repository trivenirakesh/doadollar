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
            'name' => 'Super Admin',
            'status' => 1,
        ]);

        Role::create([
            'name' => 'Manager',
            'status' => 1,
        ]);

        Role::create([
            'name' => 'User',
            'status' => 1,
        ]);
    }
}
