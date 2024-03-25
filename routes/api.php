<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get("/d",function(){
    return "sakmkmas";
});

//Route::prefix("filemanagement")->group(function (){

    Route::prefix("auth")->controller(\App\Http\Controllers\UserController::class)->group(function (){

        Route::post("register","Register");
        Route::post("login","Login");
        Route::delete("logout","Logout");
    });
    Route::prefix("profile")->controller(\App\Http\Controllers\ProfileController::class)->group(function (){
        Route::post("create","create");
        Route::get("show","show");
        Route::post("update","update");
        Route::post("delete","destroy");
    });
    Route::prefix("cource")->controller(\App\Http\Controllers\CoursController::class)->group(function (){
        Route::post("create","create");
        Route::get("index","index");
        Route::post("update","update");
        Route::get("delete/{id}","destroy");
    });

Route::prefix("paper")->controller(\App\Http\Controllers\Papers\PaperController::class)->group(function (){
        Route::post("create","create");
        Route::get("index","index");
        Route::get("show/{id}","show");

    Route::post("update","update");
        Route::get("delete/{id}","destroy");
    });Route::prefix("exam")->controller(\App\Http\Controllers\Exam\ExameController::class)->group(function (){
        Route::post("create","create");
        Route::get("index","index");
        Route::get("show/{id}","show");
        Route::post("update","update");
        Route::get("delete/{id}","destroy");
    });

//Route::prefix("form")->controller(\App\Http\Controllers\FormController::class)->group(function (){
//        Route::post("create","create");
//        Route::get("index","index");
//        Route::post("update","update");
//        Route::get("delete/{id}","destroy");
//    });

Route::prefix("center")->controller(\App\Http\Controllers\CenterController::class)->group(function (){
        Route::post("create","create");
        Route::get("index","index");
     //   Route::get("show/{id}","show");
        Route::post("update","update");
        Route::get("delete/{id}","destroy");
    });

Route::prefix("online")->controller(\App\Http\Controllers\OnlineController::class)->group(function (){
    Route::post("create","create");
    Route::get("index","index");
   // Route::get("show/{id}","show");
    Route::post("update","update");
    Route::get("delete/{id}","destroy");
});

Route::prefix("adviser")->controller(\App\Http\Controllers\AdviserController::class)->group(function (){
        Route::post("create","create");
        Route::get("index","index");
    //    Route::get("show/{id_user}","show");//all date whith status
        Route::post("update","update");
        Route::get("delete/{id}","destroy");
    });
Route::prefix("date")->controller(\App\Http\Controllers\DateController::class)->group(function (){
        Route::post("create","create");
    //for adv
        Route::get("index/{id}","index");//all status date which aval
        Route::get("show/{status}/{id}","show");//all status reserve
        Route::post("update","update");
        Route::get("delete/{id}","destroy");
    });
Route::prefix("reserve")->controller(\App\Http\Controllers\ReserveController::class)->group(function (){

    //for user
    Route::get("index/{id}","index");//all status date
    Route::get("show/{status}","show");//all status reserve
    Route::post("create","create");
    Route::post("check","check");
    });
Route::prefix("favorite")->controller(\App\Http\Controllers\FavoriteController::class)->group(function (){

    //for user
    Route::get("index","index");//all status date
    Route::post("create","create");
    });
Route::prefix("rate")->controller(\App\Http\Controllers\RateController::class)->group(function (){

    //for user
    Route::post("create","create");
    });

//////hamza
///
Route::controller(\App\Http\Controllers\AuthenticationController::class)
    ->prefix("auth")->group(function () {
        Route::post("signup", "register");
//        Route::post("login", "login");
//        Route::post("ActiveEmail", "ActiveEmail");
//        Route::post("checkEmail", "checkEmail");
//        Route::post("resendActiveEmail", "resendActiveEmail");
//        Route::post("resetPassWord", "resetPassWord");
//        Route::post("verifycodeforgetpassword", "verifycodeforgetpassword");
//        Route::post('auth/logout',  'logout');
//        //->middleware('auth:sanctum');
   });
