<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::all()->toArray();
        return array_reverse($orders);
        $user = auth()->user();
        Log::info('User #'. $user->uuid.' '. $user->full_name. ' Entered Order index');
    }
    public function store(Request $request)
    {
        $request['uuid'] = (string) Str::orderedUuid();
        $order = new Order($request->all());
        $order->save();
        $user = auth()->user();
        Log::info('User #'. $user->uuid.' '. $user->full_name. ' Saved Order #'. $order->uuid);

        return response()->json('Order created!');
    }
    public function show($id)
    {
        $order = Order::find($id);
        $user = auth()->user();
        Log::info('User #'. $user->uuid.' '. $user->full_name. ' Have watched Order #'. $order->uuid);
        return response()->json($order);
    }
    public function update($id, Request $request)
    {
        $order = Order::find($id);
        $order->update($request->all());
        $user = auth()->user();
        Log::info('User #'. $user->uuid.' '. $user->full_name. ' Updated Order #'. $order->uuid);
        return response()->json('Order updated!');
    }
    public function destroy($id)
    {
        $order = Order::find($id);
        $order->delete();
        $user = auth()->user();
        Log::info('User #'. $user->uuid.' '. $user->full_name. ' Deleted Order #'. $order->uuid);
        return response()->json('Order deleted!');
    }

}
