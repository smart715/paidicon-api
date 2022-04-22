<?php

namespace App\Http\Middleware;

use App\Models\AdminSetting;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use App\Facades\ApiRateLimiter;
use Illuminate\Support\Facades\Route;

class ApiThrottle
{

    protected $ignoreRoutes = [
        'clientpluginsettings.show',
        'clientpluginsettings.store'
    ];

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse|JsonResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $settings = AdminSetting::query()->first();
        if (!$settings || !in_array(Route::currentRouteName(), $this->ignoreRoutes)) {
            return $next($request);
        }

        $tooManyAttempts = true;
        ApiRateLimiter::attempt(
            'api_throttle',
            $settings->ip_requests_limit,
            function () use (&$tooManyAttempts) {
                $tooManyAttempts = false;
            },
            $settings->ip_requests_limit_seconds,
            $settings->ip_requests_interval_after_limit_seconds
        );

        if ($tooManyAttempts) {
           return response()->json([], 429);
        }


        return $next($request);
    }
}
