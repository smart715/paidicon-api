<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Str;

class NotificationController extends Controller
{

    public function index()
    {
        $notifications = Notification::all()->toArray();
        return array_reverse($notifications);      
    }
    public function store(Request $request)
    {
        $request['uuid'] = (string) Str::orderedUuid();
        $notification = new Notification($request->all());
        $notification->save();
        return response()->json('Notification created!');
    }
    public function show($id)
    {
        $notification = Notification::find($id);
        return response()->json($notification);
    }
    public function update($id, Request $request)
    {
        $notification = Notification::find($id);
        $notification->update($request->all());
        return response()->json('Notification updated!');
    }
    public function destroy($id)
    {
        $notification = Notification::find($id);
        $notification->delete();
        return response()->json('Notification deleted!');
    }

}
