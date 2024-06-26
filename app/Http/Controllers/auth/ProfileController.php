<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Profile;
use App\MyApplication\MyApp;
use App\MyApplication\Services\FileRuleValidation;
use App\MyApplication\Services\ProfileRuleValidation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @property ProfileController rules
 */
class ProfileController extends Controller
{

    public function __construct()
    {
        $this->middleware(["auth:sanctum","multi.auth:2"])->except('displayprofile');
        $this->middleware(["auth:sanctum","multi.auth:0|1"])->only('displayprofile');
        $this->rule = new ProfileRuleValidation();
    }


    public function Create(Request $request)
    {
      //  dd($request->name);
        if (!Profile::query()->exists(auth()->id())) {

            try {
                DB::beginTransaction();
                $profileAdded = Profile::create([
                    "id_user" => auth()->id(),
                    "name" => strtolower($request->name),
                    "lastName" => strtolower($request->lastName),
                    "fatherName" => strtolower($request->fatherName),
                    "gender" => strtolower($request->gender),
                    "birthDate" => $request->birthDate,
                    "mobilePhone" => $request->mobilePhone,
                    "specialization" => strtolower($request->specialization),
                    "levelEducational" => strtolower($request->levelEducational),
                ]);
                DB::commit();
                return MyApp::Json()->dataHandle($profileAdded, "profile");
            } catch (\Exception $e) {

                DB::rollBack();
                throw new \Exception($e->getMessage());
            }

        } else

            return MyApp::Json()->errorHandle("profile", "انت تملك برفايل لا يمكنك انشاء واحد جديد");//,$prof->getErrorMessage);

    }

    public function show()
    {
        if (Profile::query()->where("id_user", auth()->id())->exists()) {

            try {

                DB::beginTransaction();
                $profileGet = Profile::where(
                    "id_user", auth()->id())->get();
                DB::commit();
                return MyApp::Json()->dataHandle($profileGet, "profile");
            } catch (\Exception $e) {

                DB::rollBack();
                throw new \Exception($e->getMessage());
            }

        } else

            return MyApp::Json()->errorHandle("profile", "لقد حدث خطا ما اعد المحاولة لاحقا");//,$prof->getErrorMessage);

    }

    public function update(Request $request)
    {
        if (Profile::query()->where("id_user", auth()->id())->exists()) {
            try {
                DB::beginTransaction();

                $p = Profile::where("id_user", auth()->id())->first();
                if ($p) {
                    $p->name = strtolower($request->name);
                    $p->lastName = strtolower($request->lastName);
                    $p->fatherName = strtolower($request->fatherName);
                    $p->gender = strtolower($request->gender);
                    $p->birthDate = $request->birthDate;
                    $p->mobilePhone = $request->mobilePhone;
                    $p->specialization = strtolower($request->specialization);
                    $p->levelEducational = strtolower($request->levelEducational);
                    $p->save();
                }
                DB::commit();
                return MyApp::Json()->dataHandle("edit successfully", "profile");
            } catch (\Exception $e) {

                DB::rollBack();
                throw new \Exception($e->getMessage());
            }

        } else

            return MyApp::Json()->errorHandle("profile", "انت لا تملك برفايل لتعديله");//,$prof->getErrorMessage);


    }

    public function destroy()
    {
        if (Profile::query()->where("id_user", auth()->id())->exists()) {
            try {

                DB::beginTransaction();
                Profile::where("id_user", auth()->id())->delete();
                DB::commit();
                return MyApp::Json()->dataHandle("success", "profile");
            } catch (\Exception $e) {

                DB::rollBack();
                throw new \Exception($e->getMessage());
            }

        } else

            return MyApp::Json()->errorHandle("profile", "انت لا تملك برفايل لحذفه");//,$prof->getErrorMessage);


    }
    public function displayprofile($id)
    {
        if (Profile::query()->where("id", $id)->exists()) {
            try {

                DB::beginTransaction();
               $data= Profile::where("id", $id)->get();
                DB::commit();
                return MyApp::Json()->dataHandle($data, "data");
            } catch (\Exception $e) {

                DB::rollBack();
                throw new \Exception($e->getMessage());
            }

        } else

            return MyApp::Json()->errorHandle("data", "حدث خطا ما ");//,$prof->getErrorMessage);


    }
}
