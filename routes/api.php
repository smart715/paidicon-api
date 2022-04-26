<?php

use App\Http\Controllers\API\CustomNotificationController;
use App\Http\Controllers\API\CustomEmailController;
use App\Http\Controllers\API\EmailHistoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\AdminSettingController;
use App\Http\Controllers\API\APIKeyController;
use App\Http\Controllers\API\ClientPluginSettingBackupController;
use App\Http\Controllers\API\EmailTemplateController;
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

Route::middleware('api.throttle')->group(function() {
    Route::resource('users', UserController::class);
    Route::resource('orders', OrderController::class);
    Route::resource('packages', PackageController::class);
    Route::resource('transactions', TransactionController::class);
    Route::resource('apikeys', APIKeyController::class);
    Route::resource('clientpluginsettings', ClientPluginSettingBackupController::class);
    Route::resource('notifications ', NotificationController::class);
    Route::resource('email-template', EmailTemplateController::class);
    Route::resource('email-history', EmailHistoryController::class);
    Route::resource('adminsettings', AdminSettingController::class);

    Route::post('notification-custom/send', [CustomNotificationController::class,'send']);
    Route::post('notification-custom/send-multiple', [CustomNotificationController::class,'sendMultiple']);
    Route::post('email-custom/send', [CustomEmailController::class, 'send']);
    Route::post('email-custom/send-multiple', [CustomEmailController::class, 'sendMultiple']);
});
