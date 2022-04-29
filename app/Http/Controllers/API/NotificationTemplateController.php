<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\NotificationTemplate;
use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;


class NotificationTemplateController extends Controller
{

    public function index()
    {
        $notifications = NotificationTemplate::all()->toArray();
        return array_reverse($notifications);
        $user = auth()->user();
        Log::info('User #'. $user->uuid.' '. $user->full_name. ' Entered Notification Template index');
    }
    public function store(Request $request)
    {
        $request['uuid'] = (string) Str::orderedUuid();
        $notificationTemplate = new NotificationTemplate($request->all());
        $notificationTemplate->save();
        $user = auth()->user();
        Log::info('User #'. $user->uuid.' '. $user->full_name. ' Saved Notification Template #'. $notificationTemplate->uuid);

        return response()->json('Notification Template created!');
    }
    public function show($id)
    {
        $notificationTemplate = NotificationTemplate::find($id);
        $user = auth()->user();
        Log::info('User #'. $user->uuid.' '. $user->full_name. ' Have watched Notification Template #'. $notificationTemplate->uuid);
        return response()->json($notificationTemplate);
    }
    public function update($id, Request $request)
    {
        $notificationTemplate = NotificationTemplate::find($id);
        $notificationTemplate->update($request->all());
        $user = auth()->user();
        Log::info('User #'. $user->uuid.' '. $user->full_name. ' Updated Notification Template #'. $notificationTemplate->uuid);
        return response()->json('Notification updated!');
    }
    public function destroy($id)
    {
        $notificationTemplate = NotificationTemplate::find($id);
        $notificationTemplate->delete();
        $user = auth()->user();
        Log::info('User #'. $user->uuid.' '. $user->full_name. ' Deleted Notification Template #'. $notificationTemplate->uuid);
        return response()->json('Notification deleted!');
    }

}
