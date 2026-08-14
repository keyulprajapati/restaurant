<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]
            ->forgetCachedPermissions();

        /*
        |--------------------------------------------------------------------------
        | Permissions
        |--------------------------------------------------------------------------
        */

        $permissions = [

            // Dashboard
            'dashboard.view',

            // Orders
            'orders.view',
            'orders.create',
            'orders.edit',
            'orders.delete',
            'orders.update-status',

            // POS
            'pos.view',
            'pos.create-order',

            // Kitchen
            'kitchen.view',
            'kitchen.update-status',

            // Menu
            'menu.view',
            'menu.create',
            'menu.edit',
            'menu.delete',

            // Categories
            'categories.view',
            'categories.create',
            'categories.edit',
            'categories.delete',

            // Tables
            'tables.view',
            'tables.create',
            'tables.edit',
            'tables.delete',

            // Reservations
            'reservations.view',
            'reservations.create',
            'reservations.edit',
            'reservations.delete',

            // Customers
            'customers.view',
            'customers.create',
            'customers.edit',
            'customers.delete',

            // Payments
            'payments.view',
            'payments.create',
            'payments.refund',

            // Reports
            'reports.view',
            'reports.export',

            // Users
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',

            // Roles
            'roles.view',
            'roles.create',
            'roles.edit',
            'roles.delete',

            // Settings
            'settings.view',
            'settings.edit',
        ];

        foreach ($permissions as $permission) {

            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);

        }

        /*
        |--------------------------------------------------------------------------
        | Super Admin
        |--------------------------------------------------------------------------
        */

        $superAdmin = Role::firstOrCreate([
            'name' => 'Super Admin',
            'guard_name' => 'web',
        ]);

        $superAdmin->syncPermissions(
            Permission::all()
        );

        /*
        |--------------------------------------------------------------------------
        | Manager
        |--------------------------------------------------------------------------
        */

        $manager = Role::firstOrCreate([
            'name' => 'Manager',
            'guard_name' => 'web',
        ]);

        $manager->syncPermissions([

            'dashboard.view',

            'orders.view',
            'orders.create',
            'orders.edit',
            'orders.update-status',

            'pos.view',
            'pos.create-order',

            'kitchen.view',
            'kitchen.update-status',

            'menu.view',
            'menu.create',
            'menu.edit',

            'categories.view',
            'categories.create',
            'categories.edit',

            'tables.view',
            'tables.create',
            'tables.edit',

            'reservations.view',
            'reservations.create',
            'reservations.edit',

            'customers.view',
            'customers.create',
            'customers.edit',

            'payments.view',

            'reports.view',
            'reports.export',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Cashier
        |--------------------------------------------------------------------------
        */

        $cashier = Role::firstOrCreate([
            'name' => 'Cashier',
            'guard_name' => 'web',
        ]);

        $cashier->syncPermissions([

            'dashboard.view',

            'orders.view',
            'orders.create',
            'orders.edit',

            'pos.view',
            'pos.create-order',

            'customers.view',
            'customers.create',
            'customers.edit',

            'payments.view',
            'payments.create',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Kitchen
        |--------------------------------------------------------------------------
        */

        $kitchen = Role::firstOrCreate([
            'name' => 'Kitchen',
            'guard_name' => 'web',
        ]);

        $kitchen->syncPermissions([

            'dashboard.view',

            'orders.view',
            'orders.update-status',

            'kitchen.view',
            'kitchen.update-status',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Waiter
        |--------------------------------------------------------------------------
        */

        $waiter = Role::firstOrCreate([
            'name' => 'Waiter',
            'guard_name' => 'web',
        ]);

        $waiter->syncPermissions([

            'dashboard.view',

            'orders.view',
            'orders.create',
            'orders.edit',

            'tables.view',

            'customers.view',
            'customers.create',

            'reservations.view',
        ]);
    }
}