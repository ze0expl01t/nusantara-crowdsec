<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@yourdomain.com'],
            [
                'name' => 'Administrator',
                'password' => '$2y$12$vhV7AkTLGgB9jkPNk.MDtuepuHEjh8U86GUSEIVvqwPKHVmhMbMta',
                'email_verified_at' => now(),
            ]
        );
    }
}
