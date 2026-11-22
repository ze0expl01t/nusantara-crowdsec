<?php

namespace App\Http\Middleware;

use App\Models\AuditLog;
use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class VerifyBrandCredit
{
    public const DEFAULT_SALT = 'ARKAN_SECRET_SALT_2026';
    public const EXPECTED_CREDIT = 'arkanintermedia';
    public const COOLDOWN_SECONDS = 300;

    public function handle(Request $request, Closure $next): Response
    {
        try {
            $credit = Setting::get('brand_credit');
            $storedHash = (string) (Setting::get('brand_credit_hash') ?? '');
            $salt = (string) (config('app.brand_credit_salt') ?: env('BRAND_CREDIT_SALT') ?: self::DEFAULT_SALT);
            $expectedHash = hash('sha256', self::EXPECTED_CREDIT . $salt);

            $isCreditMismatch = empty($credit) || $credit !== self::EXPECTED_CREDIT;
            $isHashMismatch = $storedHash === '' || !hash_equals($expectedHash, $storedHash);

            if ($isCreditMismatch || $isHashMismatch) {
                $lock = Cache::lock('verify_brand_credit_tamper_lock', 10);

                if ($lock->get()) {
                    try {
                        $cooldownKey = 'verify_brand_credit_tamper_cooldown';

                        if (!Cache::has($cooldownKey)) {
                            Cache::put($cooldownKey, true, self::COOLDOWN_SECONDS);

                            AuditLog::create([
                                'action' => 'brand_credit_tamper',
                                'target' => 'settings.brand_credit',
                                'actor'  => $request->user()?->email ?? 'anonymous',
                                'meta'   => [
                                    'detected_value' => $credit,
                                    'expected_value' => self::EXPECTED_CREDIT,
                                    'hash_valid'     => !$isHashMismatch,
                                    'ip'             => $request->ip(),
                                    'user_agent'     => substr((string) $request->userAgent(), 0, 255),
                                ],
                            ]);
                        }

                        Setting::updateOrCreate(['key' => 'brand_credit'], ['value' => self::EXPECTED_CREDIT]);
                        Setting::updateOrCreate(['key' => 'brand_credit_hash'], ['value' => $expectedHash]);

                        if ($request->hasSession()) {
                            session()->flash('brand_tamper_detected', now()->format('d M Y H:i:s'));
                        }
                    } finally {
                        $lock->release();
                    }
                }
            }
        } catch (\Throwable $e) {
            \Log::warning('VerifyBrandCredit error: ' . $e->getMessage());
        }

        return $next($request);
    }
}
