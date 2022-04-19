<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Package;
use Illuminate\Support\Str;

class PackageController extends Controller
{

    public function index()
    {
        $packages = Package::all()->toArray();
        return array_reverse($packages);      
    }
    public function store(Request $request)
    {
        $request['uuid'] = (string) Str::orderedUuid();
        $package = new Package($request->all());
        $package->save();
        return response()->json('Package created!');
    }
    public function show($id)
    {
        $package = Package::find($id);
        return response()->json($package);
    }
    public function update($id, Request $request)
    {
        $package = Package::find($id);
        $package->update($request->all());
        return response()->json('Package updated!');
    }
    public function destroy($id)
    {
        $package = Package::find($id);
        $package->delete();
        return response()->json('Package deleted!');
    }

}
