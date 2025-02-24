<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            // User management
            ['name' => 'view-users', 'description' => 'View users'],
            ['name' => 'create-users', 'description' => 'Create users'],
            ['name' => 'edit-users', 'description' => 'Edit users'],
            ['name' => 'delete-users', 'description' => 'Delete users'],

            // Bakery management
            ['name' => 'view-bakeries', 'description' => 'View bakeries'],
            ['name' => 'create-bakeries', 'description' => 'Create bakeries'],
            ['name' => 'edit-bakeries', 'description' => 'Edit bakeries'],
            ['name' => 'delete-bakeries', 'description' => 'Delete bakeries'],

            // Expense management
            ['name' => 'view-expenses', 'description' => 'View expenses'],
            ['name' => 'create-expenses', 'description' => 'Create expenses'],
            ['name' => 'approve-expenses', 'description' => 'Approve expenses'],
            ['name' => 'delete-expenses', 'description' => 'Delete expenses'],

            // Transaction management
            ['name' => 'view-transactions', 'description' => 'View transactions'],
            ['name' => 'create-transactions', 'description' => 'Create transactions'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
    }
}