<?php

namespace App\Http\Controllers\course;

use App\Http\Controllers\Controller;
use App\Models\Rate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class RateController extends Controller
{ public function __construct()
{
    $this->middleware('auth:sanctum');
}

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => ['required', Rule::exists("online_centers", "id")],
            'value' => ['required','numeric'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                "error" => $validator->errors()->all()[0],
                "status" => "failure",
                "message" => "error!"
            ]);
        }
        $rate = Rate::where([
            'id_online_center' => $request->id,
            'id_user' => Auth::id()
        ])->first();

        if (!is_null($rate)) {
            return response()->json([
                "message" => " انت تملك تقييم سابق لهذا المنتج",
                "status" => "success",
            ]);
        } else {

            Rate::create([
                'id_online_center' => $request->id,
                'value' => $request->value,
                'id_user' =>Auth::id()
            ]);
            return response()->json([
                "message" => "done",
                "status" => "success",
            ]);
        }
    }


}
