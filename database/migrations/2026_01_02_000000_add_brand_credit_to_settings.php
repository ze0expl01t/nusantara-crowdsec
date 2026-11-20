<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (! Schema::hasTable('settings')) {
            return;
        }

        // Sisipkan kredit default jika belum ada
        if (!DB::table('settings')->where('key', 'brand_credit')->exists()) {
            DB::table('settings')->insert([
                'key' => 'brand_credit',
                'value' => 'arkanintermedia',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        // Hash yang disimpan sebagai "sidik jari" dari kredit
        if (!DB::table('settings')->where('key', 'brand_credit_hash')->exists()) {
            DB::table('settings')->insert([
                'key' => 'brand_credit_hash',
                'value' => hash('sha256', 'arkanintermedia' . 'ARKAN_SECRET_SALT_2026'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
    public function down(): void {
        if (! Schema::hasTable('settings')) {
            return;
        }

        DB::table('settings')->whereIn('key', ['brand_credit', 'brand_credit_hash'])->delete();
    }
};
