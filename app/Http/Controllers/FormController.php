<?php

namespace App\Http\Controllers;

use App\Models\d4;
use App\MyApplication\MyApp;
use App\MyApplication\Services\PollFormRuleValidation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @property PollFormRuleValidation rules
 */
class FormController extends Controller
{
    public function __construct()
{
    $this->middleware(["auth:sanctum"]);
    $this->rules = new PollFormRuleValidation();
}

    public function index()
    {

        try {

            DB::beginTransaction();
            $formGet = d4::query()->get();
            DB::commit();
            return MyApp::Json()->dataHandle($formGet, "form");
        } catch (\Exception $e) {

            DB::rollBack();
            throw new \Exception($e->getMessage());
        }



        return MyApp::Json()->errorHandle("form", "لقد حدث خطا ما اعد المحاولة لاحقا");//,$prof->getErrorMessage);

    }

    public function Create(Request $request)
    {
        $request->validate($this->rules->onlyKey(["name","address"],true));
        try {
            DB::beginTransaction();
            $formAdded = d4::create([
                "name" => strtolower($request->name),
                "address" => strtolower($request->address),
            ]);
            DB::commit();
            return MyApp::Json()->dataHandle($formAdded, "form");
        } catch (\Exception $e) {

            DB::rollBack();
            throw new \Exception($e->getMessage());
        }



        return MyApp::Json()->errorHandle("form", "error");//,$prof->getErrorMessage);

    }


    public function update(Request $request)
    {
        if (d4::query()->where("id", $request->id)->exists()) {
            try {
                DB::beginTransaction();
                $p = d4::where("id", $request->id)->first();
                if ($p) {
                    $p->name = strtolower($request->name);
                    $p->address = strtolower($request->address);
                    $p->save();
                }
                DB::commit();
                return MyApp::Json()->dataHandle("edit successfully", "form");
            } catch (\Exception $e) {

                DB::rollBack();
                throw new \Exception($e->getMessage());
            }

        } else

            return MyApp::Json()->errorHandle("form", "حدث خطا ما في التعديل ");//,$prof->getErrorMessage);


    }

    public function destroy($id)
    {
        if (d4::query()->where("id", $id)->exists()) {
            try {

                DB::beginTransaction();
                d4::where("id", $id)->delete();
                DB::commit();
                return MyApp::Json()->dataHandle("success", "form");
            } catch (\Exception $e) {

                DB::rollBack();
                throw new \Exception($e->getMessage());
            }

        } else

            return MyApp::Json()->errorHandle("form", "حدث خطا ما في الحذف ");//,$prof->getErrorMessage);


    }
}
