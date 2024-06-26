<?php

namespace App\Http\Middleware;

use App\Facades\ApiRateLimiter;
use App\Models\AdminSetting;
use App\Models\ApiKey;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class ApiThrottleByKey
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse|JsonResponse
     */
    public function handle(Request $request, Closure $next)
    {

        $user = auth()->user();
        if ($user && Gate::any(['isAdmin', 'isSuperAdmin'])) {
            return (new ApiThrottle)->handle($request, $next);
        }

        $apiKeyUuid = $request->header('x-api-key');
        if (!$apiKeyUuid) {
            return response()->json(['message' => 'Api key is not present'], 404);
        }



        $apiKey = ApiKey::query()->where('uuid', $apiKeyUuid)->first();
        $ip = $request->ip();
        if (!$apiKey || gethostbyname($apiKey->restrict_to_ip_address) != $ip) {
            return response()->json(['message' => 'Your IP is unknown for this API key'], 403);
        }

        $setting = AdminSetting::query()->first();
        if ($setting && $setting->maintenance_mode) {
            return response()->json(
                ['message' => 'Site is in maintenance mode until ' . $setting->maintenance_end],
                503
            );
        }

        $tooManyAttempts = true;
        ApiRateLimiter::attempt(
            'api_throttle_by_key',
            $apiKey->ip_requests_limit,
            function () use (&$tooManyAttempts) {
                $tooManyAttempts = false;
            },
            $apiKey->ip_requests_limit_seconds,
            $apiKey->ip_requests_interval_after_limit_seconds
        );

        if ($tooManyAttempts) {
            return response()->json([], 429);
        }


        return $next($request);
    }
}
