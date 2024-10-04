<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\SubscriptionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
dd('this');
    return view('welcome');
});

Route::post('/subscribe', [SubscriptionController::class, 'subscribe']);

Route::group(['prefix' => 'auth'], function () {
    Route::post('/send-code', [VerificationController::class, 'sendCode']);
    Route::post('/verify-email', [VerificationController::class, 'verifyEmail']);
});
