<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'Dashboard' => ['dashboard'],
            'Website Management' => ['view-websites', 'create-websites', 'edit-websites'],
            'Bank Account Management' => ['view-banks', 'create-banks', 'edit-banks'],
            'Email Template Management' => ['view-email-templates', 'create-email-templates', 'edit-email-templates'],
            'Customer Type Management' => ['view-customer-types', 'create-customer-types', 'edit-customer-types', 'delete-customer-types'],

            // Quote Management Permissions
            'Quote Management' => [
                'view-quotes',
                'create-quotes',
                'edit-quotes',
                'delete-quotes',
                'view-web-inquiries',
                'view-accepted-quotes',
                'view-archived-quotes',
                'view-updated-quotes',
                'proceed-to-updated-quote',
                'proceed-to-accepted-quote',
                'proceed-to-archived-quote',
                'proceed-to-job-card',
                'proceed-to-booking',
                'proceed-to-sale',
                'proceed-to-invoice',
                'mark-as-paid',
            ],


            'User Management' => ['view-users', 'create-users', 'edit-users'],
            'Role Management' => ['view-roles', 'create-roles', 'edit-roles'],
        ];

        $data = [];
        foreach ($permissions as $parent => $permission) {
            foreach ($permission as $value) {
                $display_name = ucwords(str_replace('-', ' ', $value));
                // $display_name = ucwords(collect(explode('-', $value))
                //     ->slice(0, -1)
                //     ->implode(' '));
                if (!Permission::where('parent_name', $parent)->where('display_name', $display_name)->where('name', $value)->where('guard_name', 'web')->exists()) {
                    $data[] = [
                        'parent_name' => $parent,
                        'display_name' => $display_name,
                        'name' => $value,
                        'guard_name' => 'web',
                        'created_at' => now(),
                    ];
                }
            }
        }
        Permission::insert($data);
    }
}
