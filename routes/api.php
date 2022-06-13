<?php

use App\Http\Controllers\API\APIKeyClientController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CustomNotificationController;
use App\Http\Controllers\API\CustomEmailController;
use App\Http\Controllers\API\EmailHistoryController;
use App\Http\Controllers\API\NotificationTemplateController;
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


Route::post('login', [AuthController::class, 'login']);
Route::post('authorization', [AuthController::class, 'authorization']);

Route::middleware(['api.throttle', 'auth:api'])->group(function() {
    Route::get('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'me']);
    Route::resource('users', UserController::class);
    Route::resource('orders', OrderController::class);
    Route::resource('packages', PackageController::class);

    Route::post('transactions/order', [TransactionController::class, 'order']);
    Route::post('transactions/refund/{id}', [TransactionController::class, 'refund']);
    Route::post('transactions/referral-payment', [TransactionController::class, 'requestReferralPayment']);
    Route::post('transactions/referral-payment/{id}', [TransactionController::class, 'editReferralPaymentRequest']);
    Route::post('transactions/update-ach', [TransactionController::class, 'updateACHPayment']);

    Route::get('transactions/', [TransactionController::class, 'index']);
    Route::get('transactions/{id}', [TransactionController::class, 'show']);
    Route::delete('transactions/{id}', [TransactionController::class, 'destroy']);

    Route::resource('apikeys', APIKeyController::class);
    Route::resource('notifications', NotificationController::class);
    Route::resource('notification-templates', NotificationTemplateController::class);
    Route::resource('email-template', EmailTemplateController::class);
    Route::resource('email-history', EmailHistoryController::class);
    Route::resource('adminsettings', AdminSettingController::class)
        ->middleware('can:isSuperAdmin');

    Route::post('notification-custom/send', [CustomNotificationController::class,'send']);
    Route::post('notification-custom/send-multiple', [CustomNotificationController::class,'sendMultiple']);
    Route::post('email-custom/send', [CustomEmailController::class, 'send']);
    Route::post('email-custom/send-multiple', [CustomEmailController::class, 'sendMultiple']);
});

Route::resource('clientpluginsettings', ClientPluginSettingBackupController::class);

Route::get('/apikey/info', [APIKeyClientController::class, 'getKeyInfo']);
Route::put('/apikey/change-ip', [APIKeyClientController::class, 'changeIP']);

