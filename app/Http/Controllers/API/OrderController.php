<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Str;
class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::all()->toArray();
        return array_reverse($orders);      
    }
    public function store(Request $request)
    {
        $request['uuid'] = (string) Str::orderedUuid();
        $order = new Order($request->all());
        $order->save();
        return response()->json('Order created!');
    }
    public function show($id)
    {
        $order = Order::find($id);
        return response()->json($order);
    }
    public function update($id, Request $request)
    {
        $order = Order::find($id);
        $order->update($request->all());
        return response()->json('Order updated!');
    }
    public function destroy($id)
    {
        $order = Order::find($id);
        $order->delete();
        return response()->json('Order deleted!');
    }

}
