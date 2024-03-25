<?php

namespace App\Http\Controllers;

use App\Models\Cours;
use App\Models\Date;
use App\Models\File;
use App\MyApplication\MyApp;
use App\MyApplication\Services\CoursesRuleValidation;
use App\MyApplication\Services\FileRuleValidation;
use App\MyApplication\Services\ProfileRuleValidation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CoursController extends Controller
{
    public function __construct()
    {
        $this->middleware(["auth:sanctum"]);
        $this->rules = new CoursesRuleValidation();
    }

    public function index(): JsonResponse
    {
        $course = Cours::query()->get();
        return MyApp::Json()->dataHandle($course,"course");
    }

    public function create(Request $request): JsonResponse
    {
        $request->validate($this->rules->onlyKey(["name","file","about"],true));
        $file = $request->file("file");
        if ($file->isValid()){
            try {
                DB::beginTransaction();
                $path = MyApp::uploadFile()->upload($file);
                $courceAdded = Cours::create([
                    "about" => strtolower($request->about),
                    "name" => strtolower($request->name),
                    "path" => $path,
                ]);
                DB::commit();

                return MyApp::Json()->dataHandle($courceAdded,"cours");
            }catch (\Exception $e){
                MyApp::uploadFile()->rollBackUpload();
                DB::rollBack();
                throw new \Exception($e->getMessage());
            }
        }else{
            return MyApp::Json()->errorHandle("course",$file->getErrorMessage());
        }
    }

    public function update(Request $request): JsonResponse
    {
        // $request->validate($this->rules->onlyKey(["name","file","about"],true));
        $file = Cours::where("id",$request->id)->first();
        $oldPath = $file->path;
        $newFile = $request->file("file");
        if ($newFile->isValid()){
            try {
                DB::beginTransaction();
                $newPath = MyApp::uploadFile()->upload($newFile);
                $file->update([
                    "about" => strtolower($request->about),
                    "name" => strtolower($request->name),
                    "path" => $newPath,
                ]);
                MyApp::uploadFile()->deleteFile($oldPath);
                DB::commit();
                return MyApp::Json()->dataHandle("Successfully updated course.","message");
            }catch (\Exception $e){
                MyApp::uploadFile()->rollBackUpload();
                DB::rollBack();
                throw new \Exception($e->getMessage(),$e->getCode());
            }
        }
        return MyApp::Json()->errorHandle("file",$newFile->getErrorMessage());
    }
//في غلط بالحزف
    public function destroky($id): JsonResponse
    {
      // $request->validate($this->rules->onlyKey(["id"],true));
        $file = Cours::where("id",$id)->first();
try {

    DB::beginTransaction();
         $temp_path = $file->path;
         $file->delete();
//dd(MyApp::uploadFile()->deleteFile($temp_path));
    if (MyApp::uploadFile()->deleteFile($temp_path)) {
             DB::commit();
      //  dd("file->path");
             return MyApp::Json()->dataHandle("Successfully deleted file .", "message");
         }
     }
     catch (\Exception $e){
         MyApp::uploadFile()->rollBackUpload();
         DB::rollBack();
         throw new \Exception($e->getMessage(),$e->getCode());
     }
      //  DB::rollBack();
        return MyApp::Json()->errorHandle("file","the File current is not deleted .");
    }
    public function destroy($id): JsonResponse
    {
        if (Cours::query()->where("id", $id)->exists()) {
            try {


                    $file = Cours::where("id",$id)->first();

                    DB::beginTransaction();
                    $temp_path = $file->path;
                    $file->delete();
                    if (MyApp::uploadFile()->deleteFile($temp_path)) {
                        DB::commit();
                        return MyApp::Json()->dataHandle("Successfully deleted file .", "message");
                    }
                }  catch (\Exception $e) {

                DB::rollBack();
                throw new \Exception($e->getMessage());
            }

        } else

            return MyApp::Json()->errorHandle("date", "حدث خطا ما في الحذف  لديك ");//,$prof->getErrorMessage);


    }

}
