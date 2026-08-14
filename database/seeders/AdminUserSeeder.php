<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            [
                'email' => 'admin@restaurant.com',
            ],
            [
                'name' => 'Restaurant Admin',
                'password' => 'password',
            ]
        );

        $admin->assignRole('Super Admin');
    }
}