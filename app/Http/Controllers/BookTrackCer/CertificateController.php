<?php

namespace App\Http\Controllers\BookTrackCer;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Course;
use App\MyApplication\MyApp;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CertificateController extends Controller
{

    public function index(): JsonResponse
    {        $this->middleware(["auth:sanctum"]);

        $data = Certificate::query()->get();
        return MyApp::Json()->dataHandle($data, "data");
    }



    public function create(Request $request): JsonResponse
    {
        if (isset($request['photo']) && $request['photo']->isValid()) {
            try {
                // Check if a photo file was uploaded
                // Generate a unique file name
                $photoPath = $request->file('photo')->store('file'); // The file will be stored in the 'public/Uploads' directory

                DB::beginTransaction();
                //   dd($path);
                $Added = Certificate::create([
                    "photo" => ($photoPath),
                ]);

                DB::commit();
                return MyApp::Json()->dataHandle($Added, "data");
            } catch (\Exception $e) {
                MyApp::uploadFile()->deleteFile($photoPath) ;
                DB::rollBack();
                throw new \Exception($e->getMessage());
            }
        } else {
            return MyApp::Json()->errorHandle("data",'حدث خطا ما اعد المحاوله لاحقا');
        }
    }


    public function delete($id): JsonResponse
    {
        if (Certificate::query()->where("id", $id)->exists()) {
            try {


                $file = Certificate::where("id", $id)->first();
                DB::beginTransaction();
                $temp_path = $file->photo;
                $file->delete();
                if (MyApp::uploadFile()->deleteFile('photo/',$temp_path,'Uploads/file/'));
                {DB::commit();
                    return MyApp::Json()->dataHandle("Successfully deleted  .", "data");
                }
            } catch (\Exception $e) {

                DB::rollBack();
                throw new \Exception($e->getMessage());
            }

        } else

            return MyApp::Json()->errorHandle("data", "حدث خطا ما في الحذف  لديك ");//,$prof->getErrorMessage);


    }
}
