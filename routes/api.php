<?php

use App\Http\Controllers\advisor\AdviserController;
use App\Http\Controllers\advisor\DateController;
use App\Http\Controllers\advisor\ReserveController;
use App\Http\Controllers\auth\AuthenticationController;
use App\Http\Controllers\auth\ProfileController;
use App\Http\Controllers\auth\UserController;
use App\Http\Controllers\BookTrackCer\BookingController;
use App\Http\Controllers\course\CenterController;
use App\Http\Controllers\course\CoursController;
use App\Http\Controllers\course\FavoriteController;
use App\Http\Controllers\course\OnlineController;
use App\Http\Controllers\course\RateController;
use App\Http\Controllers\Exam\ExameController;
use App\Http\Controllers\course\FileController;
use App\Http\Controllers\Papers\PaperController;
use App\Http\Controllers\course\VideoController;
use App\Http\Controllers\StatisticController;
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
Route::get("/d", function () {
    return "sakmkmas";
});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});




Route::prefix("user")->group(function () {
    Route::prefix("home")->controller(CoursController::class)->group(function () {
        Route::get("common", "common");
        Route::get("all", "all");
        Route::post("search", "search");
        Route::get("each/{type}", "each");

    });
    Route::prefix("favorite")->controller(FavoriteController::class)->group(function () {

        //for user
        Route::get("index", "index");//all status date
        Route::post("create", "create");
    });
    Route::prefix("rate")->controller(RateController::class)->group(function () {
        //for user
        Route::post("create", "create");
    });
    Route::prefix("booking")->controller(BookingController::class)->group(function () {
        //for user
        Route::get("create/{id}", "create");
    });

    Route::get('center/show/{id}', [CenterController::class, 'show']);


    Route::get('online/show/{id}', [OnlineController::class, 'show']);
    Route::get('video/{id}', [VideoController::class, 'show']);
    Route::get('file/{id}', [FileController::class, 'show']);
    Route::get('file/{id}', [FileController::class, 'show']);
    Route::get("display/{type}", [AdviserController::class, 'display']);

    Route::prefix("profile")->
   // controller(ProfileController::class)->
    group(function () {
        Route::post("create",  [ProfileController::class, 'create']);
        Route::get("show",  [ProfileController::class, 'show']);
        Route::post("update",  [ProfileController::class, 'update']);;
        Route::post("delete",  [ProfileController::class, 'destroy']);
    });


});


//------------
Route::prefix("auth")->controller(UserController::class)->group(function () {

    Route::post("register", "Register");
    Route::post("login", "Login");
    Route::delete("logout", "Logout");
});

Route::prefix("booking")->controller(BookingController::class)->group(function () {
    Route::post("check/{id}", "check");
    Route::post("index/{type}/{id}", "index");
});
//------------
Route::prefix("course")->controller(CoursController::class)->group(function () {
    Route::post("create", "create");
    Route::get("index", "index");
    Route::get("indexname", "indexname");
    Route::get("displaydetils/{id}", "displaydetils");
    Route::post("update/{id}", "update");
    Route::get("delete/{id}", "delete");
});
Route::prefix("center")->controller(CenterController::class)->group(function () {
    Route::post("create", "create");
    Route::get("index", "index");
    //   Route::get("show/{id}","show");
    Route::post("update", "update");
    Route::get("delete/{id}", "destroy");
});
Route::prefix("online")->controller(OnlineController::class)->group(function () {
    Route::post("create", "create");
    Route::get("index", "index");
    // Route::get("show/{id}","show");
    Route::post("update", "update");
    Route::get("delete/{id}", "destroy");
});
Route::prefix("paper")->controller(PaperController::class)->group(function () {
    Route::post("create", "create");
    Route::post("addQuestions", "addQuestions");
    Route::post("deleteQusetions", "deleteQusetions");
    Route::get("index/{type}", "index");
    Route::get("indexname/{type}", "indexname");
    Route::get("show/{id}", "show");
    Route::get("delete/{id}", "delete");

});
Route::prefix("exam")->controller(ExameController::class)->group(function () {
    Route::post("create", "create");
    Route::post("addQuestions", "addQuestions");
    Route::post("deleteQusetions", "deleteQusetions");
    Route::get("index", "index");
    Route::get("show/{id}", "show");
    Route::get("delete/{id}", "delete");
});
//------------
Route::prefix("adviser")->controller(AdviserController::class)->group(function () {
    Route::post("create", "create");
    Route::get("index", "index");
    Route::get("show/{id_adviser}","show");
    Route::post("update", "update");
    Route::get("delete/{id}", "delete");
});
Route::prefix("date")->controller(DateController::class)->group(function () {
    Route::post("create/{id_adviser}", "create");
    //for adv
    Route::get("index/{id}/{type}", "index");//all status date which aval
    Route::get("showday/{id_adviser}/{d}", "showday");//all status date which aval
    Route::get("show/{status}/{id}", "show");//all status reserve
    Route::post("update", "update");
    Route::get("delete/{id}", "destroy");
});
Route::prefix("reserve")->controller(ReserveController::class)->group(function () {
    //for user
    Route::get("user/index/{id}", "index");//الموعيد المتاحه
    Route::get("user/show", "show");//موعيدي
    Route::post("user/create", "create");
    Route::post("check", "check");
});


//------------hamza
Route::controller(AuthenticationController::class)
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
//_________________________________________Statistics

//for user
Route::get("proportion",  [StatisticController::class, 'proportion']);
Route::get("count",  [StatisticController::class, 'count']);
Route::get("statistic",  [StatisticController::class, 'statistic']);
Route::get("advisernow",  [StatisticController::class, 'advisernow']);
