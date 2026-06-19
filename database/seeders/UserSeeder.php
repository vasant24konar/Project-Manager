<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::upsert([
            [
                'name'     => 'Admin User',
                'email'    => 'admin@example.com',
                'password' => Hash::make('Admin@1234'),
                'role'     => User::ROLE_ADMIN,
            ],
            [
                'name'     => 'Product Manager',
                'email'    => 'manager@example.com',
                'password' => Hash::make('Manager@1234'),
                'role'     => User::ROLE_PRODUCT_MANAGER,
            ],
            [
                'name'     => 'Customer User',
                'email'    => 'user@example.com',
                'password' => Hash::make('User@1234!'),
                'role'     => User::ROLE_CUSTOMER,
            ],
        ], ['email'], ['name', 'password', 'role']);
    }
}
