<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ApiKey;
use Illuminate\Support\Str;

class APIKeyController extends Controller
{

    public function index()
    {
        $apikeys = ApiKey::all()->toArray();
        return array_reverse($apikeys);      
    }
    public function store(Request $request)
    {
        $request['uuid'] = (string) Str::orderedUuid();
        $apikey = new ApiKey($request->all());
        $apikey->save();
        return response()->json('Api Key created!');
    }
    public function show($id)
    {
        $apikey = ApiKey::find($id);
        return response()->json($apikey);
    }
    public function update($id, Request $request)
    {
        $apikey = ApiKey::find($id);
        $apikey->update($request->all());
        return response()->json('Api Key updated!');
    }
    public function destroy($id)
    {
        $apikey = ApiKey::find($id);
        $apikey->delete();
        return response()->json('Api Key deleted!');
    }

}
