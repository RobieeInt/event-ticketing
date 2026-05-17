<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name'            => 'Admin',
                'password'        => Hash::make('password'),
                'role'            => 'admin',
                'status'          => 'active',
                'otp_verified_at' => now(),
            ]
        );

        $this->command->info('Admin user created: admin@example.com / password');
    }
}
