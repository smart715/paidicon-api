<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmailTemplate;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class EmailTemplateController extends Controller
{
    public function index()
    {
        $emails = EmailTemplate::all()->toArray();
        return array_reverse($emails);
        $user = auth()->user();
        Log::info('User #'. $user->uuid.' '. $user->full_name. ' Entered Email Template index');
    }
    public function store(Request $request)
    {
        $request['uuid'] = (string) Str::orderedUuid();
        $email = new EmailTemplate($request->all());
        $email->save();
        $user = auth()->user();
        Log::info('User #'. $user->uuid.' '. $user->full_name. ' Saved Email Template #'. $email->uuid);

        return response()->json('Email created!');
    }
    public function show($id)
    {
        $email = EmailTemplate::find($id);
        $user = auth()->user();
        Log::info('User #'. $user->uuid.' '. $user->full_name. ' Have watched Email Template #'. $email->uuid);
        return response()->json($email);
    }
    public function update($id, Request $request)
    {
        $email = EmailTemplate::find($id);
        $email->update($request->all());
        $user = auth()->user();
        Log::info('User #'. $user->uuid.' '. $user->full_name. ' Updated Email Template #'. $email->uuid);
        return response()->json('Email updated!');
    }
    public function destroy($id)
    {
        $email = EmailTemplate::find($id);
        $email->delete();
        $user = auth()->user();
        Log::info('User #'. $user->uuid.' '. $user->full_name. ' Deleted Email Template #'. $email->uuid);
        return response()->json('Email deleted!');
    }

}
