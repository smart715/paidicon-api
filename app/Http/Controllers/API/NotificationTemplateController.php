<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\NotificationTemplate;
use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Str;

class NotificationTemplateController extends Controller
{

    public function index()
    {
        $notifications = NotificationTemplate::all()->toArray();
        return array_reverse($notifications);
    }
    public function store(Request $request)
    {
        $request['uuid'] = (string) Str::orderedUuid();
        $notificationTemplate = new NotificationTemplate($request->all());
        $notificationTemplate->save();
        return response()->json('Notification Template created!');
    }
    public function show($id)
    {
        $notificationTemplate = NotificationTemplate::find($id);
        return response()->json($notificationTemplate);
    }
    public function update($id, Request $request)
    {
        $notificationTemplate = NotificationTemplate::find($id);
        $notificationTemplate->update($request->all());
        return response()->json('Notification updated!');
    }
    public function destroy($id)
    {
        $notificationTemplate = NotificationTemplate::find($id);
        $notificationTemplate->delete();
        return response()->json('Notification deleted!');
    }

}
