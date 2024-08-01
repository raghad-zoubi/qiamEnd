<?php

namespace App\Http\Controllers\course;

use App\Http\Controllers\Controller;
use App\Http\Resources\FileCourses;
use App\Http\Resources\VideoCourses;
use App\Models\Content;
use App\Models\file;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FileController extends Controller
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
            $file = Content::
            with(['file'])->
            where('id',$id)->get();


        }catch (\Exception $e) {

            DB::rollBack();
            throw new \Exception($e->getMessage());
        }


        return response()->json([
            'status' => 'success',
            'message' => 'Files retrieved successfully.',
            'file' => $file[0]['file']
                //FileCourses::collection($file)
        ]);



    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(file $file)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, file $file)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(file $file)
    {
        //
    }
}
