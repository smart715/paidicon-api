<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\EmailHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EmailHistoryController extends Controller
{

    public function index()
    {
        $settings = EmailHistory::all()->toArray();
        return array_reverse($settings);
    }

    public function show($id)
    {
        $setting = EmailHistory::find($id);
        return response()->json($setting);
    }

    public function destroy($id)
    {
        $setting = EmailHistory::find($id);
        $setting->delete();
        return response()->json('Email History deleted!');
    }
}
