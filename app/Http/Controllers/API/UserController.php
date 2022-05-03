<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class UserController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all()->toArray();
        $authUser = auth()->user();
        Log::info('User #'. $authUser->uuid.' '. $authUser->full_name. ' Entered User index');

        return array_reverse($users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request['uuid'] = (string) Str::orderedUuid();
        $request['referral_code'] = (string) Str::orderedUuid();
        $request['password'] = Hash::make($request->get('password'));

        $user = new User($request->all());
        //return $request;
        $user->save();
        $authUser = auth()->user();
        Log::info('User #'. $authUser->uuid.' '. $authUser->full_name. ' Saved User #'. $user->uuid);

        return response()->json('User created!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        $authUser = auth()->user();
        Log::info('User #'. $authUser->uuid.' '. $authUser->full_name. ' Have watched User #'. $user->uuid);
        return response()->json($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id,Request $request)
    {
        if(Gate::check('isSuperAdmin') || auth()->id() === $id) {
            $user = User::find($id);
            $request['password'] = Hash::make($request->get('password'));
            $user->update($request->all());
            $authUser = auth()->user();
            Log::info('User #'. $authUser->uuid.' '. $authUser->full_name. ' Updated User #'. $user->uuid);
            return response()->json('User updated!');
        }
        return response()->json('Forbidden!',403);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();
        $authUser = auth()->user();
        Log::info('User #'. $authUser->uuid.' '. $authUser->full_name. ' Deleted User #'. $user->uuid);
        return response()->json('User deleted!');
    }
}
