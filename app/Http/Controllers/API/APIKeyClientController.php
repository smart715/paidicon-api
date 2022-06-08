<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ApiKey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class APIKeyClientController extends Controller
{

    public function __construct()
    {
          $this->middleware('api.throttleByKey')
              ->only(['getKeyInfo']);

        $this->middleware('api.throttle')
            ->only(['changeIP']);
    }

    public function changeIP(Request $request): \Illuminate\Http\JsonResponse
    {
        $key = 'changeIP'.$request->header('x-api-key').$request->ip();
        $tries = Cache::remember($key, 300, function() {
            return 3;
        });
        if(intval($tries) <= 0) {
            return response()->json(['message' => 'Try again later'], 429);
        }
        Cache::decrement($key);
        $apiKey = ApiKey::where('uuid', $request->header('x-api-key'))->firstOrFail();
        if($request->get('previous_ip') === $apiKey->restrict_to_ip_address) {
            $apiKey->previous_ip_address = $apiKey->restrict_to_ip_address;
            $apiKey->restrict_to_ip_address = $request->get('new_ip');
            $apiKey->save();
            Log::info('Client #'. $apiKey.' Changed IP to '. $apiKey->restrict_to_ip_address);
            return response()->json(['message' => 'API key IP changed successfully']);
        }

        return response()->json(['message' => 'Wrong IP address. You have '.$tries.' more retries'], 400);
    }

    public function getKeyInfo(Request $request)
    {
        $apiKey = ApiKey::where('uuid', $request->header('x-api-key'))->first();
        Log::info('Client #'. $apiKey.' Entered API KEY info page');

        return response()->json(
            [
                'expiration_date' => $apiKey->expires,
                'previous_ip' => $apiKey->previous_ip_address
            ]
        );
    }
}
