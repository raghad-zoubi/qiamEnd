<?php


use App\Http\Controllers\advisor\AdviserController;
use App\Http\Controllers\advisor\DateController;
use App\Http\Controllers\advisor\ReserveController;
use App\Http\Controllers\auth\AuthenticationController;
use App\Http\Controllers\auth\EmployeeController;
use App\Http\Controllers\auth\ProfileController;
use App\Http\Controllers\auth\UserController;
use App\Http\Controllers\BookTrackCer\BookingController;
use App\Http\Controllers\BookTrackCer\CertificateController;
use App\Http\Controllers\BookTrackCer\UserCertificateController;
use App\Http\Controllers\course\CenterController;
use App\Http\Controllers\course\ContentController;
use App\Http\Controllers\course\CoursController;
use App\Http\Controllers\course\FavoriteController;
use App\Http\Controllers\course\OnlineCenterController;
use App\Http\Controllers\course\OnlineController;
use App\Http\Controllers\course\RateController;
use App\Http\Controllers\Exam\CourseExameController;
use App\Http\Controllers\Exam\ExameController;
use App\Http\Controllers\course\FileController;
use App\Http\Controllers\Exam\ReExamController;
use App\Http\Controllers\InformationController;
use App\Http\Controllers\NotifactionController;
use App\Http\Controllers\Papers\CoursePaperController;
use App\Http\Controllers\Papers\PaperController;
use App\Http\Controllers\course\VideoController;
use App\Http\Controllers\StatisticController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
Route::get('/sendbook/{id_book}/{status}', [NotifactionController::class, 'sendNotificationBooking'])
    ->middleware('auth:sanctum');
Route::get('/send/{id_reserve}/{status}', [NotifactionController::class, 'sendNotificationReserve'])
    ->middleware('auth:sanctum');
Route::get('/sendre/{id_reserve}/{status}', [NotifactionController::class, 'sendNotificationReExam'])
    ->middleware('auth:sanctum');

Route::get('/sendmark/{id_book}', [NotifactionController::class, 'sendNotificationAddMark'])
    ->middleware('auth:sanctum');

Route::get('/indexnoti', [NotifactionController::class, 'listNotifications'])
    ->middleware('auth:sanctum');


Route::get('/num', [CourseExameController::class, 'generateNextSerialNumber']);
Route::post('/framep', [ContentController::class, 'extractFrame']);
Route::get('/frameg/{video_path}', [ContentController::class, 'extractFrameg']);
Route::get('/converto', [ContentController::class, 'convertVideo']);
Route::get('/getinfo', [ContentController::class, 'getVideoInfo']);
Route::get("/beb",  [CourseExameController::class, 'generateCertificatebeb']);
Route::post("/addText",  [UserCertificateController::class, 'addText']);




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
        Route::get("create/{id}", "create");
    });
    Route::prefix("rate")->controller(RateController::class)->group(function () {
        Route::post("create", "create");
    });
    Route::prefix("profile")->group(function () {
        Route::post("create",  [ProfileController::class, 'create']);
        Route::get("show",  [ProfileController::class, 'show']);
        Route::post("update",  [ProfileController::class, 'update']);;
        Route::post("delete",  [ProfileController::class, 'destroy']);
    });

    Route::get('center/show/{id}', [CenterController::class, 'show']);
    Route::get('online/show/{id}', [OnlineController::class, 'show']);
    Route::get('still', [OnlineController::class, 'still']);
    Route::get('done', [OnlineController::class, 'done']);
    Route::get('wait', [OnlineController::class, 'wait']);
    Route::get('content/{id_content}', [ContentController::class, 'show']);
    Route::get('video/{id}', [VideoController::class, 'show']);
    Route::get('afterVideo/{id}/{endTime}', [VideoController::class, 'afterVideo']);
    Route::get('file/{id}', [FileController::class, 'show']);
    Route::post('/extract-frame', [ContentController::class, 'extractFrame']);
    Route::get('/information/indexuser', [InformationController::class, 'indexUser']);
    Route::prefix("bookingCourse")->controller(BookingController::class)->group(function () {
        //for user
        Route::get("book/{id}", "book");
        Route::post("create/{id}", "create");
        });
    //advisor
    Route::prefix("advisor")->group(function () {

        Route::get("display/{type}", [AdviserController::class, 'display']);
    Route::get("deteils/{id_adviser}", [AdviserController::class, 'deteils']);
    Route::get("displayDate/{id_adviser}/{day}", [ReserveController::class, 'displayDate']);
    Route::get("displayDay/{id_adviser}", [ReserveController::class, 'displayDay']);
        Route::post("create", [ReserveController::class,"create"]);//الموعيد المتاحه
    Route::get("present/{type}", [ReserveController::class,"present"]);//موعيدي
//
    });
//bookingCourse
    Route::prefix("paper")->
    group(function () {
        Route::get("show/{id}",  [CoursePaperController::class, 'show']);
      Route::post("answer",  [CoursePaperController::class, 'answer'])->middleware('auth:sanctum');;
    });

    Route::prefix("exam")->group(function () {
        Route::get("course/{id_online_center}",
            [CourseExameController::class, 'showExamCourse']);
        Route::post("course",
            [CourseExameController::class, 'answerExamCourse']);
        Route::get("content/{id_content}",
            [CourseExameController::class, 'showExamContent']);
        Route::post("content",
            [CourseExameController::class, 'answerExamContent']);

        Route::get("poll/{id_online_center}",
            [CourseExameController::class, 'showPollCourse']);

        Route::post("poll/{id_online_center}",
            [CourseExameController::class, 'answerPollCourse']);

    });
    Route::prefix("reExam")->
    group(function () {
        Route::get("create/{id_online_center}",  [ReExamController::class, 'create']);
        Route::get("myindex/{type}",  [ReExamController::class, 'myindex']);

    });
    Route::prefix("certificate")->group(function () {


        Route::get("index",
            [UserCertificateController::class, 'myCertificate']);

    });


});
//bookingCourse indexNew
//*************************************************************************
//------------
//Route::prefix("auth")->controller(UserController::class)->group(function () {
//
//    Route::post("register", "Register");
//    Route::post("login", "Login");
//    Route::delete("logout", "Logout");
//}); ok
//------------auth
Route::controller(AuthenticationController::class)
    ->prefix("auth")->group(function () {
        Route::post("signup", "register");
        Route::post("login", "login");
        Route::post("ActiveEmail", "ActiveEmail");
        Route::post("checkEmail", "checkEmail");
        Route::post("resendActiveEmail", "resendActiveEmail");
        Route::post("resetPassWord", "resetPassWord");
        Route::get("deleteAcount", "deleteAcount");
        Route::post("verifycodeforgetpassword", "verifycodeforgetpassword");
        Route::post('logout',  'logout');
        Route::post('/fcmtoken', 'fcmToken');

    });
////////////////////////////////
Route::prefix("booking")->controller(BookingController::class)->group(function () {
    Route::post("check/{id}", "check");
    Route::get("indexNew", "indexNew");
    Route::get("indexOk/{id}", "indexOk");
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
    Route::get("showContent/{id}","showContent");//id==idonline
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

});Route::prefix("paper")->controller(CoursePaperController::class)->
group(function () {
    Route::get("displayUser/{id_user}/{id_online_center}", "displayPaperUser");
    Route::get("displayPaper/{id_online_center}", "displayPaperCourse");


});
Route::prefix("course")->
group(function () {
    Route::get("displayCopy/{id_course}",  [OnlineCenterController::class, 'displayCopy']);
    Route::get("detailsOnlineCopy/{id_online_center}",  [OnlineCenterController::class, 'detailsOnlineCopy']);
    Route::get("detailsCenterCopy/{id_online_center}",  [OnlineCenterController::class, 'detailsCenterCopy']);
    Route::get("deleteCopy/{id_online_center}",  [OnlineCenterController::class, 'deleteCopy']);
    Route::post("activateCopy/{id_online_center}",  [OnlineCenterController::class, 'activateCopy']);
});
Route::prefix("profile")->
group(function () {
    Route::get("displayprofile/{id}",  [ProfileController::class, 'displayprofile']);

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
    Route::post("update/{id}", "update");
    Route::get("delete/{id}", "delete");
});
Route::prefix("date")->controller(DateController::class)->group(function () {
    Route::post("create/{id_adviser}", "create");
    //for adv
    Route::get("index/{id}/{type}", "index");//all status date which aval
    Route::get("showday/{id_adviser}/{d}", "showday");//all status date which aval
    Route::get("show/{status}/{id}", "show");//all status reserve
    Route::post("update", "update");
    Route::get("delete/{id}", "delete");
});
Route::prefix("reserve")->controller(ReserveController::class)->group(function () {
    //for user
    Route::get("user/index/{id}", "index");//الموعيد المتاحه
    Route::get("user/show", "show");//موعيدي
    Route::post("user/create", "create");
    Route::post("check", "check");
});



//_________________________________________Statistics

//for user

Route::get("proportion",  [StatisticController::class, 'proportion']);
Route::get("count",  [StatisticController::class, 'count']);
Route::get("statistic/{year}",  [StatisticController::class, 'statistic']);
Route::get("advisernow",  [StatisticController::class, 'advisernow']);
//-----------------------------------Information
Route::prefix("information")->
group(function () {
    Route::post("create",  [InformationController::class, 'create']);
    Route::get("index",  [InformationController::class, 'index']);
    Route::post("update",  [InformationController::class, 'update']);;
});
Route::prefix("employee")->
group(function () {
    Route::post("create",  [EmployeeController::class, 'create']);
    Route::get("index",  [EmployeeController::class, 'index']);
    Route::get("indexAll",  [EmployeeController::class, 'indexAll']);
    Route::post("update",  [EmployeeController::class, 'update']);
    Route::post("resetpass",  [EmployeeController::class, 'resetPassword']);
    Route::get("delete/{id}",  [EmployeeController::class, 'delete']);
    Route::post("login",  [EmployeeController::class, 'login']);
    Route::post('/fcmtoken',  [EmployeeController::class, 'fcmToken']);

});
Route::prefix("certificate")->
group(function () {
    Route::post("create",  [CertificateController::class, 'create']);
    Route::get("index",  [CertificateController::class, 'index']);
    Route::get("delete/{id}",  [CertificateController::class, 'delete']);
    Route::get("all",  [UserCertificateController::class, 'index']);
});Route::prefix("reExam")->
group(function () {
    Route::get("index",  [ReExamController::class, 'index']);
    Route::post("check/{id_reExam}",  [ReExamController::class, 'check']);

});Route::prefix("exam")->
group(function () {

    Route::post("addMark/{id_book}",
        [CourseExameController::class, 'addMrakToUser']);

});
//*****************************
//Route::get("www/{id}",  [CoursController::class, 'displaydetils']);

//
//Route::get('/test-image', function () {
//    $img = Image::canvas(800, 600, '#ff0000');
//    return $img->response('jpg');
//});


//Route::post('/add', [UserCertificateController::class, 'addTextToImage']);
//Route::get("/d", function () {
//    return "sakmkmas";
//});
//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//})    Route::get('ra', [UserCertificateController::class, 'resizeImage']);
//in
