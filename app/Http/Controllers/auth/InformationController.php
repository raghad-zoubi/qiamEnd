<?php

namespace App\Http\Controllers;

use App\Models\Information;
use App\Models\Online_Center;
use App\MyApplication\MyApp;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InformationController extends Controller

{
    public function __construct()
    {
      // $this->middleware(["auth:sanctum"]);
    }


    public function index(): JsonResponse
    {
        $course = Information::query()->get();
        return MyApp::Json()->dataHandle($course);
    }

    public function create(Request $request): JsonResponse
    {
      try {
                // Check if a photo file was uploaded
                // Generate a unique file name

                DB::beginTransaction();
                $Added = Information::create([
                    "director" => strtolower($request->director),
                    "site" => strtolower($request->site),
                    "time" => strtolower($request->time),
                    "email"=> strtolower($request->email),
                    "nubmer" => strtolower($request->nubmer),
                    "facebook" => strtolower($request->facebook),

                ]);
                DB::commit();

                return MyApp::Json()->dataHandle($Added, "data");
            } catch (\Exception $e) {
                DB::rollBack();
                throw new \Exception($e->getMessage());
            }

            return MyApp::Json()->errorHandle("data",'حدث خطا ما اعد المحاوله لاحقا');

    }

    public function update(Request $request): JsonResponse
    {
        $file = Information::query()->first();

            try {
                DB::beginTransaction();

                // Check if a photo file was uploaded
                // Generate a unique file name

                $file->update([

                        "director" => strtolower($request->director),
                        "site" => strtolower($request->site),
                        "time" => strtolower($request->time),
                    "email"=> strtolower($request->email),
                    "nubmer" => strtolower($request->nubmer),
                    "facebook" => strtolower($request->facebook),



                ]);

                    DB::commit();
                    return MyApp::Json()->dataHandle("Successfully updated .", "data");
                } catch (\Exception $e) {
                DB::rollBack();
                throw new \Exception($e->getMessage(), $e->getCode());
            }

        return MyApp::Json()->errorHandle("file", $newFile->getErrorMessage());
    }



}
