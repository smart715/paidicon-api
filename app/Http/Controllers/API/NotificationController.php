<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;


class NotificationController extends Controller
{

    public function index()
    {
        $notifications = Notification::all()->toArray();
        $user = auth()->user();
        Log::info('User #'. $user->uuid.' '. $user->full_name. ' Entered Notification index');
        return array_reverse($notifications);
    }
    public function store(Request $request)
    {
        $request['uuid'] = (string) Str::orderedUuid();
        $notification = new Notification($request->all());
        $notification->save();
        $user = auth()->user();
        Log::info('User #'. $user->uuid.' '. $user->full_name. ' Saved Notification #'. $notification->uuid);

        return response()->json('Notification created!');
    }
    public function show($id)
    {
        $notification = Notification::findOrFail($id);
        $user = auth()->user();
        Log::info('User #'. $user->uuid.' '. $user->full_name. ' Have watched Notification #'. $notification->uuid);
        return response()->json($notification);
    }
    public function update($id, Request $request)
    {
        $notification = Notification::find($id);
        $notification->update($request->all());
        $user = auth()->user();
        Log::info('User #'. $user->uuid.' '. $user->full_name. ' Updated Notification #'. $notification->uuid);
        return response()->json('Notification updated!');
    }
    public function destroy($id)
    {
        $notification = Notification::find($id);
        $notification->delete();
        $user = auth()->user();
        Log::info('User #'. $user->uuid.' '. $user->full_name. ' Deleted Notification #'. $notification->uuid);
        return response()->json('Notification deleted!');
    }

}
