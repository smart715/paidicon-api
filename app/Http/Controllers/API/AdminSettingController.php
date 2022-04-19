<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdminSetting;
use Illuminate\Support\Str;

class AdminSettingController extends Controller
{

    public function index()
    {
        $settings = AdminSetting::all()->toArray();
        return array_reverse($settings);      
    }
    public function store(Request $request)
    {
        $request['uuid'] = (string) Str::orderedUuid();
        $setting = new AdminSetting($request->all());
        $setting->save();
        return response()->json('Admin Setting created!');
    }
    public function show($id)
    {
        $setting = AdminSetting::find($id);
        return response()->json($setting);
    }
    public function update($id, Request $request)
    {
        $setting = AdminSetting::find($id);
        $setting->update($request->all());
        return response()->json('Admin Setting updated!');
    }
    public function destroy($id)
    {
        $setting = AdminSetting::find($id);
        $setting->delete();
        return response()->json('Admin Setting deleted!');
    }

}
