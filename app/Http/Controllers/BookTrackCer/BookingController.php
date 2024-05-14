<?php

namespace App\Http\Controllers\BookTrackCer;

use App\Http\Controllers\Controller;
use App\Http\Resources\IndexNewBooking;
use App\Http\Resources\IndexOkBooking;
use App\Models\Booking;
use App\Models\CoursePaper;
use App\Models\Online_Center;
use App\MyApplication\MyApp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class BookingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }
//عرض الحجوزات كلا والموافق عليها و اللي لسا مو محددة حسب الid_onlinecenter
    //dash
    public function indexNew()

    {
        //$request->validate($this->rules->onlyKey(["id","status"], true));
            try {
                DB::beginTransaction();

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

                   }

           catch (\Exception $e) {

               DB::rollBack();
               throw new \Exception($e->getMessage());

           }

            return MyApp::Json()->errorHandle("date", "حدث خطا ما لديك ");//,$prof->getErrorMessage);


    }
    public function indexOk( $id)

    {
        //$request->validate($this->rules->onlyKey(["id","status"], true));
        if (Booking::query()->where("id_online_center", $id)->exists()) {
            try {
                DB::beginTransaction();

            $ad = Booking::
            where("id_online_center", $id)->
             where('status','=','1')->
              with('users')->
            with('bookingindex')->
              get();


                    DB::commit();
                return MyApp::Json()->dataHandle(IndexOkBooking::Collection($ad), "data");
                   }

           catch (\Exception $e) {

                DB::rollBack();
                throw new \Exception($e->getMessage());
            }

        } else

            return MyApp::Json()->errorHandle("date", "حدث خطا ما لديك ");//,$prof->getErrorMessage);


    }
    // user

    public function book( $id)
    {

        $rate = CoursePaper::where([
            'id_online_center' => $id,
        ])->get();



            return response()->json([
                "message" => $rate,
                "status" => "success",
            ]);

    }
    public function create( $id)
    {

        $rate = Booking::where([
            'id_online_center' => $id,
            'id_user' => Auth::id()
        ])->first();

        if (!is_null($rate)) {
            return response()->json([
                "message" => "حدث خطا ما يرجى المحلولة لاحقا",
                "status" => "success",
            ]);
        }
        else {

            Booking::create([
                'id_online_center' => $id,
                'mark' => 0,
                'done' => 0,
                'status' => 0,
                'id_user' =>Auth::id()
            ]);
            return response()->json([
                "message" => "done",
                "status" => "success",
            ]);
        }
    }
    // dash
    public function check(Request $request,$id)
{// في معلومات استمارة
    //$request->validate($this->rules->onlyKey(["id","status"], true));
    if (Booking::query()->where("id", $id)->exists()) {
        try {
            DB::beginTransaction();

            $ad = Booking::where(["id", $id])->first();
            if ($ad ) {
                if($request->status=='1'){
                    $ad->status = ($request->status);
                    $ad->save();
                    DB::commit();
                    return MyApp::Json()->dataHandle("bookin successfully", "date");
                }
                else{
                    $ad = Booking::query()->where("id", $request->id)->delete();
                    DB::commit();
                    return MyApp::Json()->dataHandle("booking unsuccessfully", "date");
                }}

        } catch (\Exception $e) {

            DB::rollBack();
            throw new \Exception($e->getMessage());
        }

    } else

        return MyApp::Json()->errorHandle("date", "حدث خطا ما لديك ");//,$prof->getErrorMessage);


}




}
