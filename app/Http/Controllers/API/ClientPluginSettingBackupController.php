<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClientPluginSetting;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;


class ClientPluginSettingBackupController extends Controller
{

    public function __construct()
    {
        $this->middleware('api.throttleByKey')
            ->only(['store', 'show']);
        $this->middleware('api.throttle')
            ->except(['store', 'show']);

        $this->middleware('auth')
            ->except(['store', 'show']);
    }

    public function index()
    {
        $settings = ClientPluginSetting::all()->toArray();
        $user = auth()->user();
        Log::info('User #'. $user->uuid.' '. $user->full_name. ' Entered Client Plugin Settings Backup index');
        return array_reverse($settings);
    }
    public function store(Request $request)
    {
        $request['uuid'] = (string) Str::orderedUuid();
        $setting = new ClientPluginSetting($request->all());
        $setting->save();
        $uuid = auth()->user() ? auth()->user()->uuid : $request->header(['x-api-key']);
        Log::info('User #'. $uuid. ' Saved Client Plugin Settings Backup #'. $setting->uuid);

        return response()->json('Client Plugin Setting created!');
    }
    public function show($id)
    {
        $setting = ClientPluginSetting::find($id);
        $uuid = auth()->user()->uuid;
        Log::info('User #'. $uuid. ' Have watched Client Plugin Settings Backup #'. $setting->uuid);
        return response()->json($setting);
    }
    public function update($id, Request $request)
    {
        $setting = ClientPluginSetting::find($id);
        $setting->update($request->all());
        $uuid = auth()->user() ? auth()->user()->uuid : $request->header(['x-api-key']);
        Log::info('User #'. $uuid. ' Updated Client Plugin Settings Backup #'. $setting->uuid);
        return response()->json('Client Plugin Setting updated!');
    }
    public function destroy($id)
    {
        $setting = ClientPluginSetting::find($id);
        $setting->delete();
        $uuid = auth()->user()->uuid;
        Log::info('User #'. $uuid.' Deleted Client Plugin Settings Backup #'. $setting->uuid);
        return response()->json('Client Plugin Setting deleted!');
    }

}
