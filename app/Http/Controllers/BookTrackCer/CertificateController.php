<?php

namespace App\Http\Controllers\BookTrackCer;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\MyApplication\MyApp;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CertificateController extends Controller
{

    public function index(): JsonResponse
    {
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


}
