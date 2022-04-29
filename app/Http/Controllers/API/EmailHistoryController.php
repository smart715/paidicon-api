<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\EmailHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;


class EmailHistoryController extends Controller
{

    public function index()
    {
        $settings = EmailHistory::all()->toArray();
        return array_reverse($settings);
        $user = auth()->user();
        Log::info('User #'. $user->uuid.' '. $user->full_name. ' Entered Email history index');
    }

    public function show($id)
    {
        $setting = EmailHistory::find($id);
        $user = auth()->user();
        Log::info('User #'. $user->uuid.' '. $user->full_name. ' Have watched Email history #'. $setting->uuid);
        return response()->json($setting);
    }

    public function destroy($id)
    {
        $setting = EmailHistory::find($id);
        $setting->delete();
        $user = auth()->user();
        Log::info('User #'. $user->uuid.' '. $user->full_name. ' Deleted Email history #'. $setting->uuid);
        return response()->json('Email History deleted!');
    }
}
