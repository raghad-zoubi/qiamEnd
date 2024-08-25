<?php

namespace App\Http\Controllers\BookTrackCer;
use App\Models\Notification;
use App\Services\FCMService;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Http\Resources\IndexNewBooking;
use App\Http\Resources\IndexOkBooking;
use App\Models\AnswerPaper;
use App\Models\Booking;
use App\Models\Center;
use App\Models\Content;
use App\Models\CoursePaper;
use App\Models\Information;
use App\Models\Online;
use App\Models\Online_Center;
use App\Models\Paper;
use App\Models\Track;
use App\Models\Video;
use App\MyApplication\MyApp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use function Intervention\Image\has;
use function Kreait\Firebase\RemoteConfig\user;
use function Nette\Utils\isEmpty;
use function PHPUnit\Framework\isNull;
use function PHPUnit\TextUI\executeHelpCommandWhenThereIsNothingElseToDo;

class BookingController extends Controller
{
    protected $fcmService;
    public function __construct(FCMService $fcmService)
    {
        $this->middleware('auth:sanctum');

        $this->fcmService = $fcmService;
    }
//عرض الحجوزات كلا والموافق عليها و اللي لسا مو محددة حسب الid_onlinecenter
    //dash
    public function indexNew()

    {
        //$request->validate($this->rules->onlyKey(["id","status"], true));
        try {
            DB::beginTransaction();
//CoursePaper
            $ad = Booking::
            //where("id_online_center", $id) ->
            where('status', '=', '0')
                ->with('users')
                ->with('bookingindex')
                ->orderBy('created_at', 'asc') // Order by 'created_at' column in descending order
                ->get();

            DB::commit();
            return MyApp::Json()->dataHandle(IndexNewBooking::Collection($ad), "data");
            //   return MyApp::Json()->dataHandle($ad);

        } catch (\Exception $e) {

            DB::rollBack();
            throw new \Exception($e->getMessage());

        }

        return MyApp::Json()->errorHandle("date", "حدث خطا ما لديك ");//,$prof->getErrorMessage);


    }

    public function indexOk($id)

    {
        //$request->validate($this->rules->onlyKey(["id","status"], true));
        //   if (Booking::query()->where("id_online_center", $id)->exists())
        //   if (true)
        {
            try {
                DB::beginTransaction();

                $ad = Booking::
                with('users')->
                with('bookingindex')->
                where("id_online_center", $id)->
                where('status', '=', '1')->
                get();


                DB::commit();
                if ($ad->isNotEmpty())
                    return MyApp::Json()->dataHandle(IndexOkBooking::Collection($ad), "data");
                else if (!$ad->isNotEmpty())
                    return MyApp::Json()->dataHandle('لا يوجد حجز لعرضه', "data");
            } catch (\Exception $e) {

                DB::rollBack();
                throw new \Exception($e->getMessage());
            }

        }

        return MyApp::Json()->errorHandle("date", "حدث خطا ما لديك ");//,$prof->getErrorMessage);


    }

    // dash
    public function check(Request $request, $id)
    {
        if (Booking::query()->where("id", $id)->exists()) {
            try {
                DB::beginTransaction();

                $ad = Booking::where("id", $id)->first();
                if ($ad) {
                    if ($request->status == '1') {
                        $ad->status = ($request->status);
                        $ad->save();

                        $type=Online_Center::query()->where('id',$ad->id_online_center)->first();
                        if($type->id_online!=null) {
                            $id_online_cente=$type->id;
                            $id_video=Video ::query()->
                            whereHas('content', function ($rank) use ($id_online_cente) {
                                $rank  ->where('rank','=', "0")->
                                where('id_online_center','=', $id_online_cente);
                            })->
                            where('rank','=',"0")->first();
                            $track = Track::create([
                                'id_video' => $id_video->id,
                                'id_booking' => $id,
                                'endTime' => "00:00:00",
                                'done' => "0",
                            ]);
                        }
                        $user=$this->sendNotificationBooking($ad->id, 1);

                        DB::commit();
                        return MyApp::Json()->dataHandle("bookin successfully", "date");
                    }
                    else  if ($request->status == '0'){
                        $user=$this->sendNotificationBooking($ad->id, 0);

                        $ad = Booking::query()->where("id", $request->id)->delete();
                        DB::commit();
                        return MyApp::Json()->dataHandle("unbooking successfully", "date");
                    }
                    if ($request->status == '2')
                    {
                        $user=$this->sendNotificationBooking($ad->id, 2);

                    }
                }

            } catch (\Exception $e) {

                DB::rollBack();
                throw new \Exception($e->getMessage());
            }

        } else

            return MyApp::Json()->errorHandle("date", "حدث خطا ما لديك ");//,$prof->getErrorMessage);


    }

    public function sendNotificationBooking($id_book, $status)
    {


        try {
            DB::beginTransaction();


            $partbody = DB::table('booking')->select(
                'courses.name as course_name',
                'users.fcm_token as fcm',
                'profiles.id_user as id_user',
                'profiles.name as name',
                DB::raw('DATE(booking.created_at) as createdAtbook'),
                DB::raw('DATE(online_centers.created_at) as createdAcourse')
            )
                ->join('online_centers', 'online_centers.id', '=', 'booking.id_online_center')
                ->join('courses', 'courses.id', '=', 'online_centers.id_course')
                ->join('profiles', 'profiles.id_user', '=', 'booking.id_user')
                ->join('users', 'users.id', '=', 'booking.id_user')
                ->where('booking.id', $id_book)
                ->first();


            if ($partbody) {
                $deviceTokens = [
                    $partbody->fcm];
                if ($status == 2) {
                    $body =
                        ' لقد قمت بطلب حجز لدورة ' . $partbody->course_name .
                        ' نسخة ' . $partbody->createdAcourse .
                        ' بتاريخ ' . $partbody->createdAtbook;

                    $title = 'تذكير';
                } else if ($status == 1) {
                    $body =
                        ' لقد تمت الموافقة على حجزك لدورة ' . $partbody->course_name .
                        ' نسخة ' . $partbody->createdAcourse .
                        'المقدم بتاريخ ' . $partbody->createdAtbook;

                    $title = ' ';
                } else if ($status == 0) {
                    $body =
                        ' لقد تم رفض  حجزك لدورة ' . $partbody->course_name .
                        ' نسخة ' . $partbody->createdAcourse .
                        'المقدم بتاريخ ' . $partbody->createdAtbook;


                    $title = ' ';
                }

                $data = ['key' => 'value'];

                $adviserAdded = Notification::create([
                    "title" => $title,
                    "body" => $body,
                    "id_user" => $partbody->id_user,

                ]);
                $status = $this->fcmService->sendNotification($deviceTokens, $title, $body, $data);
              //  dd($status);

                DB::commit();

            } else {
                return response()->json([
                    'massege' => 'حدث خطا ما ']);
            }


        } catch (\Exception $e) {

            DB::rollBack();
            throw new \Exception($e->getMessage());
        }


    }
    // user
    public function book($id)
    {
        try {
            $rate = Booking::where([
                'id_online_center' => $id,
                'id_user' => Auth::id()
            ])->first();

            if (!is_null($rate)) {
                return MyApp::Json()->dataHandle('أنت تملك حجز مسبقا', "data");

            }else {
                DB::beginTransaction();
                $online_center = Online_Center::where([
                    'id' => $id,
                ])->first();
                if ($online_center != null) {

                    if ($online_center->id_online != null) {
                        $isopen =
                            Online::query()
                                ->whereHas('onlineCenters', function ($query) use ($id) {
                                    $query->where('id', $id);
                                })->select('isopen')->
                                get();
                        if ($isopen[0]['isopen'] == '1') {

                            $questionsWithOptions = Paper::
                            with('questionpaperwith'
                            )->whereHas('coursepaper', function ($query) use ($id) {
                                $query->where('id_online_center', $id);
                            })->where("type", "استمارة")->
                            get();


                            DB::commit();

//            return MyApp::Json()->dataHandle($questionsWithOptions, "paper");
                            return MyApp::Json()->dataHandle($questionsWithOptions);
                        } else {


                            DB::commit();
                            return MyApp::Json()->dataHandle('غير متاح للحجز حاليا', "data");
                        }
                    }
                    else
                        if ($online_center->id_center != null) {
                            $mytime = Carbon::now();
                            $can = Center::query()
                                ->where('id', $online_center->id_center)
                                ->where('start', '<',  $mytime->toDateTimeString())
                                ->where('end', '>', $mytime->toDateTimeString())
                                ->first();
                            if ($can!=null) {

                                $questionsWithOptions = Paper::
                                with('questionpaperwith'
                                )->whereHas('coursepaper', function ($query) use ($id) {
                                    $query->where('id_online_center', $id);
                                })->where("type", "استمارة")->
                                get();


                                DB::commit();

                                return MyApp::Json()->dataHandle($questionsWithOptions);
                            } else {


                                DB::commit();
                                return MyApp::Json()->dataHandle('غير متاح للحجز حاليا', "data");
                            }

                        }

                }


                else
                    return MyApp::Json()->dataHandle('حدث خطا يرجى المحاولة لاحقا', "data");


            } } catch (\Exception $e) {

            DB::rollBack();
            throw new \Exception($e->getMessage());
        }


        return MyApp::Json()->errorHandle("paper", "لقد حدث خطا ما اعد المحاولة لاحقا");//,$prof->getErrorMessage);

    }


    public function create(Request $request, $id)
    {

        try {
            $rate = Booking::where([
                'id_online_center' => $id,
                'id_user' => Auth::id()
            ])->first();

            if (!is_null($rate)) {
                return MyApp::Json()->dataHandle('أنت تملك حجز مسبقا', "data");

            }
            else {
                DB::beginTransaction();
                $online_center = Online_Center::where([
                    'id' => $id,
                ])->first();
                if ($online_center != null) {

                    if ($online_center->id_online != null) {
                        $isopen = Online::query()
                            ->whereHas('onlineCenters', function ($query) use ($id) {
                                $query->where('id', $id);
                            })->select('isopen')->
                            get();
                        if ($isopen[0]['isopen'] == '0') {
                            return MyApp::Json()->dataHandle('غير متاح للحجز حاليا', "data");

                        } else if ($isopen[0]['isopen'] == '1') {
                            $ispapper = CoursePaper::query()
                                ->where('id_online_center', $id)
                                ->exists();

                            if ($ispapper) {
                                foreach ($request->options as $item) {
                                    if ($item != null) {
//
                                        foreach ($request->options as $item) {
                                            AnswerPaper::create([
                                                "id_user" => auth()->id(),
                                                "answer" => $item['answer'],
                                                "id_question_paper" => $item['id_question_paper'],
                                                "id_option_paper" => $item['id_option_paper']
                                            ]);
                                        }

                                        Booking::create([
                                            'id_online_center' => $id,
                                            'mark' => 0,
                                            'can' => '0',
                                            'count' => 0,
                                            'done' => '0',
                                            'status' => '0',
                                            'id_user' => Auth::id()
                                        ]);


                                        DB::commit();

                                        return response()->json([
                                            "message" => "done",
                                            "status" => "success",
                                        ]);
//                                        return MyApp::Json()->dataHandle('success', "data");

                                    } else {
//                     dd($request["options"]);
                                        return MyApp::Json()->dataHandle('يرجى تعبئة الاستمارة', "data");
                                    }
                                }
                            }
                            else if (!$ispapper) {
                                DB::beginTransaction();

                                Booking::create([
                                    'id_online_center' => $id,
                                    'mark' => 0,
                                    'can' => '0',
                                    'count' => 0,
                                    'done' => '0',
                                    'status' => '0',
                                    'id_user' => Auth::id()
                                ]);
                                return response()->json([
                                    "message" => "done",
                                    "status" => "success",
                                ]);
                            }
                            else {
                                return MyApp::Json()->dataHandle('حدث خطا يرجى المحاولة لاحقا', "data");

                            }
                        }
                    } else

                        if ($online_center->id_center != null) {
                            $mytime = Carbon::now();
                            $can = Center::query()
                                ->where('id', $online_center->id_center)
                                ->where('start', '<', $mytime->toDateTimeString())
                                ->where('end', '>', $mytime->toDateTimeString())
                                ->first();

                            if ($can != null) {

                                $ispapper = CoursePaper::query()
                                    ->where('id_online_center', $id)
                                    ->exists();

//                                if ($ispapper) {
//                                    DB::beginTransaction();
//
//                         foreach ($request->options as $item)
//                                    {
//                                    if ($item != null)
//                                        {
//
//                                            foreach ($request->options as $item) {
//                                                AnswerPaper::create([
//                                                    "id_user" => auth()->id(),
//                                                    "answer" => $item['answer'],
//                                                    "id_question_paper" => $item['id_question_paper'],
//                                                    "id_option_paper" => $item['id_option_paper']
//                                                ]);
//                                            }
//                                            Booking::create([
//                                                'id_online_center' => $id,
//                                                'mark' => 0,
//                                                'can' => '0',
//                                                'count' => 0,
//                                                'done' => '0',
//                                                'status' => '0',
//                                                'id_user' => Auth::id()
//                                            ]);
//                                            DB::commit();
//                                            return response()->json([
//                                                "message" => "done",
//                                                "status" => "success",
//                                            ]);
//
//                                    }
//                                        else {
//                                            return MyApp::Json()->dataHandle('يرجى تعبئة الاستمارة', "data");
//                                           }
//                            }
//                                }
                                if ($ispapper) {
                                    foreach ($request->options as $item) {
                                        if ($item != null) {
                                            foreach ($request->options as $item) {
                                                AnswerPaper::create([
                                                    "id_user" => auth()->id(),
                                                    "answer" => $item['answer'],
                                                    "id_question_paper" => $item['id_question_paper'],
                                                    "id_option_paper" => $item['id_option_paper']
                                                ]);
                                            }

                                            Booking::create([
                                                'id_online_center' => $id,
                                                'mark' => 0,
                                                'can' => '0',
                                                'count' => 0,
                                                'done' => '0',
                                                'status' => '0',
                                                'id_user' => Auth::id()
                                            ]);


                                            DB::commit();

                                            return response()->json([
                                                "message" => "done",
                                                "status" => "success",
                                            ]);

                                        } else {
                                            return MyApp::Json()->dataHandle('يرجى تعبئة الاستمارة', "data");
                                        }
                                    }
                                }
                                else if (!$ispapper) {
                                    DB::beginTransaction();

                                    Booking::create([
                                        'id_online_center' => $id,
                                        'mark' => 0,
                                        'can' => '0',
                                        'count' => 0,
                                        'done' => '0',
                                        'status' => '0',
                                        'id_user' => Auth::id()
                                    ]);
                                    return response()->json([
                                        "message" => "done",
                                        "status" => "success",
                                    ]);
                                } else {
                                    return MyApp::Json()->dataHandle('حدث خطا يرجى المحاولة لاحقا', "data");

                                }
                            }
                            else {
                                return MyApp::Json()->dataHandle('غير متاح للحجز حاليا', "data");

                            }
                        }

                } else
                    return MyApp::Json()->dataHandle('حدث خطا يرجى المحاولة لاحقا', "data");

            }

        }catch (\Exception $e) {

            DB::rollBack();
            throw new \Exception($e->getMessage());
        }


        return MyApp::Json()->errorHandle("paper", "لقد حدث خطا ما اعد المحاولة لاحقا");//,$prof->getErrorMessage);

    }


}
