<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Urutan eksekusi sangat penting (Users dulu, baru relasi lainnya)
        $this->call([
            UsersTableSeeder::class,
            SettingsTableSeeder::class,
            CrowdsecWhitelistedIpsTableSeeder::class,
            CrowdsecAuditLogsTableSeeder::class,
        ]);
    }
}
