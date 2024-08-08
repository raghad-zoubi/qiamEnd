<?php

namespace App\Http\Controllers\BookTrackCer;


use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Center;
use App\Models\Certificate;
use App\Models\Option;
use App\Models\Question;
use App\Models\UserCertificate;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use App\Services\ImageProcessingService;


class UserCertificateController extends Controller
{
    protected $imageProcessingService;

    public function __construct(ImageProcessingService $imageProcessingService)
    {
        $this->middleware(["auth:sanctum"]);

        $this->imageProcessingService = $imageProcessingService;
    }


    public function show($id)
    {
        // Retrieve image from database
        $image = Certificate::findOrFail($id);

        // Return the image as a response
        return response($image->image_data)->header('Content-Type', 'image/jpeg');
    }

    public function index()
    {
        // Retrieve image from database
        $responseData = UserCertificate::query()->with('book2')->get();

        // Return the image as a response
        return response()->json($responseData);

    }
   public function myCertificate()

    {



        $responseData = DB::table('user_certificate')
            ->select(
                'courses.name',
                'user_certificate.certificate',
                'user_certificate.id',
                DB::raw('CASE WHEN online_centers.id_center IS NULL THEN "online" ELSE "center" END AS type')
            )
            ->join('booking', 'booking.id', '=', 'user_certificate.id_booking')
            ->join('online_centers', 'online_centers.id', '=', 'booking.id_online_center')
            ->join('courses', 'courses.id', '=', 'online_centers.id_course')
            ->where('booking.id_user', 1)
            ->get();



        return response()->json($responseData);

    }

}

