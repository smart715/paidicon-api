<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{

    public function index()
    {
        $transactions = Transaction::all()->toArray();
        return array_reverse($transactions);
        $user = auth()->user();
        Log::info('User #'. $user->uuid.' '. $user->full_name. ' Entered Transaction index');
    }
    public function store(Request $request)
    {
        $request['uuid'] = (string) Str::orderedUuid();
        $transaction = new Transaction($request->all());
        $transaction->save();

        $user = auth()->user();
        Log::info('User #'. $user->uuid.' '. $user->full_name. ' Saved Transaction #'. $transaction->uuid);
        return response()->json('Transaction created!');
    }
    public function show($id)
    {
        $transaction = Transaction::find($id);
        $user = auth()->user();
        Log::info('User #'. $user->uuid.' '. $user->full_name. ' Have watched Transaction #'. $transaction->uuid);
        return response()->json($transaction);
    }
    public function update($id, Request $request)
    {
        $transaction = Transaction::find($id);
        $transaction->update($request->all());
        $user = auth()->user();
        Log::info('User #'. $user->uuid.' '. $user->full_name. ' Updated Transaction #'. $transaction->uuid);
        return response()->json('Transaction updated!');
    }
    public function destroy($id)
    {
        $transaction = Transaction::find($id);
        $transaction->delete();
        $user = auth()->user();
        Log::info('User #'. $user->uuid.' '. $user->full_name. ' Deleted Transaction #'. $transaction->uuid);
        return response()->json('Transaction deleted!');
    }

}
