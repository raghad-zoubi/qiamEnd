<?php

namespace App\Http\Controllers\BookTrackCer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
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
    public function index( $type,$id)

    {
        //$request->validate($this->rules->onlyKey(["id","status"], true));
        if (Booking::query()->where("id_online_center", $id)->exists()) {
            try {
                DB::beginTransaction();
if ($type=='all') {
    $ad = Booking::where(["id_online_center", $id])->get();

}
                if ($type=='ok') {
                    $ad = Booking::where(["id_online_center", $id])->
                    where('status',1)->get();

                    }
                    else {
                        $ad = Booking::where(["id_online_center", $id])->
                        where('status', 0)->get();
                    }DB::commit();
                        return MyApp::Json()->dataHandle($ad, "date");
                   }

           catch (\Exception $e) {

                DB::rollBack();
                throw new \Exception($e->getMessage());
            }

        } else

            return MyApp::Json()->errorHandle("date", "حدث خطا ما لديك ");//,$prof->getErrorMessage);


    }
    // user
    public function create( $id)
    {
//        $validator = Validator::make($request->all(), [
//            'id' => ['required', Rule::exists("online_centers", "id")],
//        ]);
//        if ($validator->fails()) {
//            return response()->json([
//                "error" => $validator->errors()->all()[0],
//                "status" => "failure",
//                "message" => "error!"
//            ]);
//        }
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
{
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
