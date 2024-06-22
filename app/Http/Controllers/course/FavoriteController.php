<?php

namespace App\Http\Controllers\course;

use App\Http\Controllers\Controller;
use App\Http\Resources\AllCourses;
use App\Models\Favorite;
use App\Models\Online_Center;
use App\MyApplication\MyApp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class FavoriteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => ['required', Rule::exists("online_centers", "id")],
        ]);
        if ($validator->fails()) {
            return response()->json([
                "error" => $validator->errors()->all()[0],
                "status" => "failure",
                "message" => "error!"
            ]);
        }
        $favorite = Favorite::where([
            'id_online_center' => $request->id,
            'id_user' => Auth::id()
        ])->first();
        if (!is_null($favorite)) {
            Auth::user()->favorite()->where('id_online_center', $request->id,)->delete();
            //  $favorite->delete();
            return response()->json([
                "message" => "delete  favorite",
                "status" => "success",
            ]);
        } else {
            Favorite::create([
                'id_online_center' => $request->id,
                'id_user' => Auth::id()
            ]);
            return response()->json([
                "message" => "add to favorite",
                "status" => "success",
            ]);
        }
    }

    public function index()
    {

        try {
            $favoriteOnlineCenterIds = Favorite::where('id_user', Auth::id())
                ->pluck('id_online_center');

            // Create a subquery to calculate average rates
            $ratesSubquery = Online_Center::leftJoin('rates', 'online_centers.id', '=', 'rates.id_online_center')
                ->selectRaw('online_centers.id, COALESCE(SUM(rates.value) / COUNT(rates.value), 0) as avg_rate')
                ->groupBy('online_centers.id')
                ->getQuery();

            // Join the subquery to the main query and fetch courses
            $courses = Online_Center::joinSub($ratesSubquery, 'subquery', function ($join) {
                $join->on('online_centers.id', '=', 'subquery.id');
            })
                ->whereIn('online_centers.id', $favoriteOnlineCenterIds) // Only fetch favorite courses
                ->with(['course', 'center'])
                ->get();

            return response()->json([
                'data' => AllCourses::collection($courses),
            ]);
        } catch (\Exception $e) {
            // Handle exception
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }



    }
}
