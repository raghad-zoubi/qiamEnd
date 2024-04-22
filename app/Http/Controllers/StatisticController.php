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
class StatisticController extends Controller
{
    public function __construct()
{
    $this->middleware(["auth:sanctum"]);
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


}
