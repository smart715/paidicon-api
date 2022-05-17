<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdminSetting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AdminSettingController extends Controller
{

    public function index()
    {

        $settings = AdminSetting::all()->toArray();
        $user = auth()->user();
        Log::info('User #'. $user->uuid.' '. $user->full_name. ' Entered setting index');
        return array_reverse($settings);
    }
    public function store(Request $request)
    {
        $request['uuid'] = (string) Str::orderedUuid();
        $setting = new AdminSetting($request->all());
        $setting->save();
        $user = auth()->user();
        Log::info('User #'. $user->uuid.' '. $user->full_name. ' Saved setting #'. $setting->uuid);
        return response()->json('Admin Setting created!');
    }
    public function show($id)
    {
        $setting = AdminSetting::find($id);
        $user = auth()->user();
        Log::info('User #'. $user->uuid.' '. $user->full_name. ' Have watched setting #'. $setting->uuid);

        return response()->json($setting);
    }
    public function update($id, Request $request)
    {
        $setting = AdminSetting::find($id);
        $setting->update($request->all());
        $user = auth()->user();
        Log::info('User #'. $user->uuid.' '. $user->full_name. ' Updated setting #'. $setting->uuid);

        return response()->json('Admin Setting updated!');
    }
    public function destroy($id)
    {
        $setting = AdminSetting::find($id);
        $setting->delete();
        $user = auth()->user();
        Log::info('User #'. $user->uuid.' '. $user->full_name. ' Deleted setting #'. $setting->uuid);

        return response()->json('Admin Setting deleted!');
    }

}
