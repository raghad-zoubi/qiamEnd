<?php

//use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});
Route::get('/test', function () {
  dd('yes');
});
//
//Route::get('push-notification', [NotificationController::class, 'index']);
//Route::post('sendNotification', [NotificationController::class, 'sendNotification'])->name('send.notification');
//
//Route::get('/m', function () {
//    return view('email-template');
//
//});
//
//
