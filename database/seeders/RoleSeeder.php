<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            ['name' => 'admin', 'description' => 'Administrator'],
            ['name' => 'manager', 'description' => 'Bakery Manager'],
            ['name' => 'accountant', 'description' => 'Accountant'],
            ['name' => 'staff', 'description' => 'Staff Member'],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}