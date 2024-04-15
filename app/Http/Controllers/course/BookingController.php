<?php

namespace App\Http\Controllers\course;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class BookingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

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


}
