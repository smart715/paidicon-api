<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ApiKey;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class APIKeyController extends Controller
{

    public function index()
    {
        $apikeys = ApiKey::all()->toArray();
        $user = auth()->user();
        Log::info('User #'. $user->uuid.' '. $user->full_name. ' Entered API KEY index');

        return array_reverse($apikeys);
    }
    public function store(Request $request)
    {
        $request['uuid'] = (string) Str::orderedUuid();
        $apikey = new ApiKey($request->all());
        $apikey->save();
        $user = auth()->user();
        Log::info('User #'. $user->uuid.' '. $user->full_name. ' Saved API KEY #'. $apikey->uuid);
        return response()->json('Api Key created!');
    }
    public function show($id)
    {
        $apikey = ApiKey::find($id);
        $user = auth()->user();
        Log::info('User #'. $user->uuid.' '. $user->full_name. ' Have watched API KEY #'. $apikey->uuid);
        return response()->json($apikey);
    }
    public function update($id, Request $request)
    {
        $apikey = ApiKey::find($id);
        $apikey->update($request->all());
        $apikey->save();
        $user = auth()->user();
        Log::info('User #'. $user->uuid.' '. $user->full_name. ' Updated API KEY #'. $apikey->uuid);
        return response()->json('Api Key updated!');
    }
    public function destroy($id)
    {
        $apikey = ApiKey::find($id);
        $apikey->delete();
        $user = auth()->user();
        Log::info('User #'. $user->uuid.' '. $user->full_name. ' Deleted API KEY #'. $apikey->uuid);

        return response()->json('Api Key deleted!');
    }

}
