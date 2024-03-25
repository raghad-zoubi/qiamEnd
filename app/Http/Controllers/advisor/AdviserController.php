<?php

namespace App\Http\Controllers;

use App\Models\Adviser;
use App\Models\d3;
use App\Models\User;
use App\MyApplication\MyApp;
use App\MyApplication\Services\AdviserRuleValidation;
use App\MyApplication\Services\PollFormRuleValidation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @property PollFormRuleValidation rules
 */
class AdviserController extends Controller
{
    public function __construct()
    {
        $this->middleware(["auth:sanctum"]);
        $this->rules = new AdviserRuleValidation();
    }

    public function index()
    {

        try {

            DB::beginTransaction();
            $adviserGet = User::

            with('adviser')->
            with('profile')
            ->get();
            DB::commit();
            return MyApp::Json()->dataHandle($adviserGet, "adviser");
        } catch (\Exception $e) {

            DB::rollBack();
            throw new \Exception($e->getMessage());
        }


        return MyApp::Json()->errorHandle("adviser", "حدث خطا ما في عرض  لديك ");//,$prof->getErrorMessage);

    }

    public function create(Request $request)
    {
        $request->validate($this->rules->onlyKey([ "type", "about", "id_user"], true));
        try {
            DB::beginTransaction();
            $adviserAdded = Adviser::create([
                "about" => strtolower($request->about),
                "type" => strtolower($request->type),
                "id_user" => $request->id_user
            ]);
            DB::commit();
            return MyApp::Json()->dataHandle($adviserAdded, "Adviser");
        } catch (\Exception $e) {

            DB::rollBack();
            throw new \Exception($e->getMessage());
        }


        return MyApp::Json()->errorHandle("adviser", "حدث خطا ما في الاضافة  لديك ");//,$prof->getErrorMessage);

    }

    public function show($id_user)
    {
        if (Adviser::query()->where("id_user", $id_user)->exists()) {

            try {

                DB::beginTransaction();
                $AdviserGet = Adviser::where("id_user", $id_user)->
                    with('date')->
                get();
                DB::commit();
                return MyApp::Json()->dataHandle($AdviserGet, "Adviser");
            } catch (\Exception $e) {

                DB::rollBack();
                throw new \Exception($e->getMessage());
            }

        } else

            return MyApp::Json()->errorHandle("Adviser", "لقد حدث خطا ما اعد المحاولة لاحقا");//,$prof->getErrorMessage);

    }

    public function update(Request $request)
    {
        $request->validate($this->rules->onlyKey([ "type", "about", "id_user"], true));

        if (Adviser::query()->where("id", $request->id)->exists()) {
            try {
                DB::beginTransaction();

                $ad = Adviser::where("id", $request->id)->first();
                if ($ad) {
                    $ad->about = strtolower($request->about);
                    $ad->type = strtolower($request->type);
                    $ad->id_user = ($request->id_user);

                    $ad->save();
                }
                DB::commit();
                return MyApp::Json()->dataHandle("edit successfully", "adviser");
            } catch (\Exception $e) {

                DB::rollBack();
                throw new \Exception($e->getMessage());
            }

        } else

            return MyApp::Json()->errorHandle("adviser", "حدث خطا ما في تعديل  لديك ");//,$prof->getErrorMessage);


    }

    public function destroy($id)
    {
        if (Adviser::query()->where("id", $id)->exists()) {
            try {

                DB::beginTransaction();
                Adviser::where("id", $id)->delete();
                DB::commit();
                return MyApp::Json()->dataHandle("success deleted", "adviser");
            } catch (\Exception $e) {

                DB::rollBack();
                throw new \Exception($e->getMessage());
            }

        } else

            return MyApp::Json()->errorHandle("adviser", "حدث خطا ما في الحذف  لديك ");//,$prof->getErrorMessage);


    }
}
