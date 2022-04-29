<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Package;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class PackageController extends Controller
{

    public function index()
    {
        $packages = Package::all()->toArray();
        $user = auth()->user();
        Log::info('User #'. $user->uuid.' '. $user->full_name. ' Entered Package index');
        return array_reverse($packages);
    }
    public function store(Request $request)
    {
        $request['uuid'] = (string) Str::orderedUuid();
        $package = new Package($request->all());
        $package->save();
        $user = auth()->user();
        Log::info('User #'. $user->uuid.' '. $user->full_name. ' Saved Package #'. $package->uuid);

        return response()->json('Package created!');
    }
    public function show($id)
    {
        $package = Package::find($id);
        $user = auth()->user();
        Log::info('User #'. $user->uuid.' '. $user->full_name. ' Have watched Package #'. $package->uuid);
        return response()->json($package);
    }
    public function update($id, Request $request)
    {
        $package = Package::find($id);
        $package->update($request->all());
        $user = auth()->user();
        Log::info('User #'. $user->uuid.' '. $user->full_name. ' Updated Package #'. $package->uuid);
        return response()->json('Package updated!');
    }
    public function destroy($id)
    {
        $package = Package::find($id);
        $package->delete();
        $user = auth()->user();
        Log::info('User #'. $user->uuid.' '. $user->full_name. ' Deleted Package #'. $package->uuid);
        return response()->json('Package deleted!');
    }

}
