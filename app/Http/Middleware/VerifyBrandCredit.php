<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Models\AuditLog;
use App\Models\Setting;
use Symfony\Component\HttpFoundation\Response;

class VerifyBrandCredit
{
    const SECRET_SALT = 'ARKAN_SECRET_SALT_2026';
    const EXPECTED_CREDIT = 'arkanintermedia';

    public function handle(Request $request, Closure $next): Response
    {
        try {
            $credit = Setting::get('brand_credit');
            $expectedHash = hash('sha256', self::EXPECTED_CREDIT . self::SECRET_SALT);
            
            // Verifikasi: apakah kredit ada DAN hash-nya cocok?
            $isTampered = false;
            if (empty($credit) || $credit !== self::EXPECTED_CREDIT) {
                $isTampered = true;
            }

            if ($isTampered) {
                // Catat ke audit log
                AuditLog::create([
                    'action' => 'brand_credit_tamper',
                    'target' => 'settings.brand_credit',
                    'actor'  => $request->user()->email ?? 'anonymous',
                    'meta'   => [
                        'detected_value' => $credit,
                        'expected_value' => self::EXPECTED_CREDIT,
                        'ip' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                    ],
                ]);

                // Auto-restore kredit
                Setting::updateOrCreate(['key' => 'brand_credit'], ['value' => self::EXPECTED_CREDIT]);
                Setting::updateOrCreate(['key' => 'brand_credit_hash'], ['value' => $expectedHash]);

                // Set session flag agar banner muncul di landing page
                session()->flash('brand_tamper_detected', now()->format('d M Y H:i:s'));
            }
        } catch (\Throwable $e) {
            // Jangan sampai error di middleware mematikan aplikasi
            \Log::warning('VerifyBrandCredit error: ' . $e->getMessage());
        }

        return $next($request);
    }
}
