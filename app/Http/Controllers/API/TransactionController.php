<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Str;

class TransactionController extends Controller
{

    public function index()
    {
        $transactions = Transaction::all()->toArray();
        return array_reverse($transactions);      
    }
    public function store(Request $request)
    {
        $request['uuid'] = (string) Str::orderedUuid();
        $transaction = new Transaction($request->all());
        $transaction->save();
        return response()->json('Transaction created!');
    }
    public function show($id)
    {
        $transaction = Transaction::find($id);
        return response()->json($transaction);
    }
    public function update($id, Request $request)
    {
        $transaction = Transaction::find($id);
        $transaction->update($request->all());
        return response()->json('Transaction updated!');
    }
    public function destroy($id)
    {
        $transaction = Transaction::find($id);
        $transaction->delete();
        return response()->json('Transaction deleted!');
    }

}
