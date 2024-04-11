<?php

namespace App\Http\Controllers\course;

use App\Http\Controllers\Controller;
use App\Http\Resources\DetailsOnlineCourses;
use App\Http\Resources\VideoCourses;
use App\Models\Content;
use App\Models\Online_Center;
use App\Models\Rate;
use App\Models\Video;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }


    public function show($id): JsonResponse
    {


        try {
            DB::beginTransaction();
            $video = Content::
            with(['video'])->
            where('id',$id)->get();


        }catch (\Exception $e) {

            DB::rollBack();
            throw new \Exception($e->getMessage());
        }


        return response()->json([
             'video' =>VideoCourses::collection($video),
            //   'video' =>($video),
        ]);


    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Video $video)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Video $video)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Video $video)
    {
        //
    }
}
