<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('settings')->delete();
        
        \DB::table('settings')->insert(array (
            0 => 
            array (
                'id' => 1,
                'key' => 'app_name',
                'value' => 'CrowdSec Portal',
                'created_at' => '2026-11-09 07:59:50',
                'updated_at' => '2026-11-09 07:59:50',
            ),
            1 => 
            array (
                'id' => 2,
                'key' => 'institution_name',
                'value' => 'General Company',
                'created_at' => '2026-11-09 07:59:50',
                'updated_at' => '2026-11-09 07:59:50',
            ),
            2 => 
            array (
                'id' => 3,
                'key' => 'logo_url',
                'value' => '',
                'created_at' => '2026-11-09 07:59:50',
                'updated_at' => '2026-11-09 07:59:50',
            ),
            3 => 
            array (
                'id' => 4,
                'key' => 'hero_title',
                'value' => 'Dokumentasi & Portal CrowdSec',
                'created_at' => '2026-11-09 07:59:50',
                'updated_at' => '2026-11-09 07:59:50',
            ),
            4 => 
            array (
                'id' => 5,
                'key' => 'hero_subtitle',
                'value' => 'Panduan lengkap untuk mengelola, memantau, dan mengamankan server Anda menggunakan CrowdSec Security Engine.',
                'created_at' => '2026-11-09 07:59:50',
                'updated_at' => '2026-11-09 07:59:50',
            ),
            5 => 
            array (
                'id' => 6,
                'key' => 'footer_text',
                'value' => 'All rights reserved.',
                'created_at' => '2026-11-09 07:59:50',
                'updated_at' => '2026-11-09 07:59:50',
            ),
            6 => 
            array (
                'id' => 8,
                'key' => 'landing_features',
                'value' => '[{"title":"Monitoring Real-time","desc":"Lihat grafik serangan dan status blokir IP secara langsung.","color":"blue","icon":"server"},{"title":"Manajemen Blokir","desc":"Kelola daftar IP yang diblokir dan lakukan Unban dengan cepat.","color":"red","icon":"shield-exclamation"},{"title":"Allowlist IP","desc":"Tambahkan IP tepercaya agar tidak pernah diblokir oleh sistem.","color":"green","icon":"lock-closed"},{"title":"Audit Log","desc":"Lihat riwayat aktivitas administratif untuk keperluan audit.","color":"orange","icon":"document-text"},{"title":"Dokumentasi","desc":"Buka panduan resmi CrowdSec di situs eksternal.","color":"blue","icon":"chart-bar"},{"title":"Pengaturan Akun","desc":"Ubah profil, kata sandi, dan preferensi tampilan.","color":"zinc","icon":"star"}]',
                'created_at' => '2026-11-09 08:07:40',
                'updated_at' => '2026-11-09 08:43:50',
            ),
        ));
        
        
    }
}