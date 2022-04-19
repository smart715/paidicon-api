<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\AdminSettingController;
use App\Http\Controllers\API\APIKeyController;
use App\Http\Controllers\API\ClientPluginSettingBackupController;
use App\Http\Controllers\API\EmailController;
use App\Http\Controllers\API\PackageController;
use App\Http\Controllers\API\NotificationController;
use App\Http\Controllers\API\TransactionController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('users', UserController::class);
Route::resource('orders', OrderController::class);
Route::resource('packages', PackageController::class);
Route::resource('transactions', TransactionController::class);
Route::resource('apikeys', APIKeyController::class);
Route::resource('clientpluginsettings', ClientPluginSettingBackupController::class);
Route::resource('notifications ', NotificationController::class);
Route::resource('email', EmailController::class);
Route::resource('adminsettings', AdminSettingController::class);