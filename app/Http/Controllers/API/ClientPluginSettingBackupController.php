<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClientPluginSetting;
use Illuminate\Support\Str;

class ClientPluginSettingBackupController extends Controller
{

    public function __construct()
    {
        $this->middleware('api.throttleByKey')
            ->only(['store', 'show']);
        $this->middleware('api.throttle')
            ->except(['store', 'show']);
    }

    public function index()
    {
        $settings = ClientPluginSetting::all()->toArray();
        return array_reverse($settings);
    }
    public function store(Request $request)
    {
        $request['uuid'] = (string) Str::orderedUuid();
        $setting = new ClientPluginSetting($request->all());
        $setting->save();
        return response()->json('Client Plugin Setting created!');
    }
    public function show($id)
    {
        $setting = ClientPluginSetting::find($id);
        return response()->json($setting);
    }
    public function update($id, Request $request)
    {
        $setting = ClientPluginSetting::find($id);
        $setting->update($request->all());
        return response()->json('Client Plugin Setting updated!');
    }
    public function destroy($id)
    {
        $setting = ClientPluginSetting::find($id);
        $setting->delete();
        return response()->json('Client Plugin Setting deleted!');
    }

}
