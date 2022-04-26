<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmailTemplate;
use Illuminate\Support\Str;
class EmailTemplateController extends Controller
{
    public function index()
    {
        $emails = EmailTemplate::all()->toArray();
        return array_reverse($emails);
    }
    public function store(Request $request)
    {
        $request['uuid'] = (string) Str::orderedUuid();
        $email = new EmailTemplate($request->all());
        $email->save();
        return response()->json('Email created!');
    }
    public function show($id)
    {
        $email = EmailTemplate::find($id);
        return response()->json($email);
    }
    public function update($id, Request $request)
    {
        $email = EmailTemplate::find($id);
        $email->update($request->all());
        return response()->json('Email updated!');
    }
    public function destroy($id)
    {
        $email = EmailTemplate::find($id);
        $email->delete();
        return response()->json('Email deleted!');
    }

}
