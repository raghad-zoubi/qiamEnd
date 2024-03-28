<?php

namespace App\Http\Controllers\course;

use App\Http\Controllers\Controller;
use App\Models\Cours;
use App\Models\Date;
use App\Models\File;
use App\MyApplication\MyApp;
use App\MyApplication\Services\CoursesRuleValidation;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


/**
 * @property CoursesRuleValidation rules
 */
class CoursController extends Controller
{
    public function __construct()
    {
//        $this->middleware(["auth:sanctum"]);
       $this->rules = new CoursesRuleValidation();
    }

    public function index(): JsonResponse
    {
        $course = Cours::query()->get();
        return MyApp::Json()->dataHandle($course,"course");
    }

    public function create(Request $request): JsonResponse
    {
        $request->validate($this->rules->onlyKey(["name","photo","about"],true));
        $file = $request->file("photo");
        if ($file->isValid()){
            try {
                DB::beginTransaction();
                $path = MyApp::uploadFile()->upload($file);
             //   dd($path);
                $courceAdded = Cours::create([
                    "about" => strtolower($request->about),
                    "name" => strtolower($request->name),
                    "photo" => strtolower($path),
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
        $request->validate($this->rules->onlyKey(["name","photo","about"],true));
        $file = Cours::where("id",$request->id)->first();
        $oldPath = $file->photo;
        $newFile = $request->file("photo");
      //  dd($newFile);
        if ($newFile->isValid()){
            try {
                DB::beginTransaction();
                $newPath = MyApp::uploadFile()->upload($newFile);
                $file->update([
                    "about" => strtolower($request->about),
                    "name" => strtolower($request->name),
                    "photo" => $newPath,
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

    public function delete($id): JsonResponse
    {
        if (Cours::query()->where("id", $id)->exists()) {
            try {


                    $file = Cours::where("id",$id)->first();

                    DB::beginTransaction();
                    $temp_path = $file->photo;
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
