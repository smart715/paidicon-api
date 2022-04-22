<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;


    /**
     * @method static \Illuminate\Cache\RateLimiter for (string $name, \Closure $callback)
     * @method static \Closure limiter(string $name)
     * @method static bool tooManyAttempts($key, $maxAttempts)
     * @method static int hit($key, $decaySeconds = 60)
     * @method static mixed attempts($key)
     * @method static mixed resetAttempts($key)
     * @method static int retriesLeft($key, $maxAttempts)
     * @method static void clear($key)
     * @method static int availableIn($key)
     * @method static bool attempt($key, $maxAttempts, \Closure $callback,$interval=60, $decaySeconds = 60)
     *
     * @see \Illuminate\Cache\RateLimiter
     */
class ApiRateLimiter extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return \App\Services\ApiRateLimiter::class;
    }
}
