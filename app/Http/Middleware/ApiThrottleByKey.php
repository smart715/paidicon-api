<?php

namespace App\Http\Middleware;

use App\Facades\ApiRateLimiter;
use App\Models\ApiKey;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiThrottleByKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse|JsonResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $apiKeyUuid = $request->header('x-api-key');
        if(!$apiKeyUuid) {
            return response()->json([], 403);
        }

        $apiKey = ApiKey::query()->where('uuid', $apiKeyUuid)->first();
        $ip = $request->ip();

        if(!$apiKey || $apiKey->restrict_to_ip_address != $ip) {
            return response()->json([], 403);
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
