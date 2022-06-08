<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use App\Models\User;
use Dotenv\Loader\Loader;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['authorization','login']]);
    }

    public function authorization(CreateUserRequest $request): \Illuminate\Http\JsonResponse
    {
        $request['uuid'] = (string)Str::orderedUuid();
        $request['referral_code'] = (string)Str::orderedUuid();
        $request['password'] = Hash::make( $request->get('password'));
        $request['role'] = 'client';

        $user = new User($request->all());
        //return $request;
        $user->save();

        Log::info('User #'. $user->uuid.' '. $user->full_name. ' registered');

        return response()->json('User created!');
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        Log::info('User'. $credentials['email']. ' logger in');
        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        $user = auth()->user();
        Log::info('User'. $user->uuid. ' requested /me');
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {

        $user = auth()->user();
        Log::info('User'. $user->uuid. ' logged out');

        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {

        $user = auth()->user();
        Log::info('User'. $user->uuid. ' refreshed his token');

        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
                                    'access_token' => $token,
                                    'token_type' => 'bearer',
                                    'expires_in' => auth()->factory()->getTTL() * 120
                                ]);
    }
}
