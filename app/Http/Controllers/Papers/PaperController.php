<?php

namespace App\Http\Controllers\Papers;

use App\Http\Controllers\Controller;
use App\Models\d1;
use App\Models\d3;
use App\Models\d6;
use App\Models\Group;
use App\Models\OptionPaper;
use App\Models\Paper;
use App\Models\QuestionPaper;
use App\MyApplication\MyApp;
use App\MyApplication\Services\PaperRuleValidation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaperController extends Controller
{

    public function __construct()
    {
     //   $this->middleware(["auth:sanctum"]);
     //   $this->rules = new PaperRuleValidation();
    }

    public function index()
    {

        try {

            DB::beginTransaction();
            $paperGet = Paper::query()->get();
            DB::commit();
            return MyApp::Json()->dataHandle($paperGet, "paper");
        } catch (\Exception $e) {

            DB::rollBack();
            throw new \Exception($e->getMessage());
        }


        return MyApp::Json()->errorHandle("paper", "لقد حدث خطا ما اعد المحاولة لاحقا");//,$prof->getErrorMessage);

    }
    public function show($id)
    {

        try {
//QuestionPaper::class, OptionPaper::class
            DB::beginTransaction();
          //  $paperGet =
//                Paper::with(["questionpaper"      =>function($q){
//                return $q->with(["optionpaper"])->get();
//                   }])->where("id",$id)
//       ->get();
//custem clim  ref
            //OptionPaper::
//            select(['id'])->whereHa('optionpaper',function ($query){
//                $query->where('optionpaper.id',1);
//            }
//
//            )
//            with(['paper'])
//                ->where('id',1)
//                ->get();

        //    Paper::find(1);
//dd("jj");
               // $environments = $paperGet->questionpaper()->with('optionpaper')->get();
                $environments = QuestionPaper::query()->with('optionpaper')

                //query()->with('QOthrough')
            ->where('id_paper',$id)->get();
                //   dd($paperGet);
                DB::commit();
                return MyApp::Json()->dataHandle($environments, "paper");
        } catch (\Exception $e) {

            DB::rollBack();
            throw new \Exception($e->getMessage());
        }


        return MyApp::Json()->errorHandle("paper", "لقد حدث خطا ما اعد المحاولة لاحقا");//,$prof->getErrorMessage);

    }

    public function Create(Request $request)
    {
        //      return MyApp::Json()->dataHandle($request['body'][0]['question'], "poll");


        //protected $fillable = ['type', 'id_poll_form', 'question', 'kind'];

        //  $request->validate($this->rules->onlyKey(["name","address"],true));
        try {
            DB::beginTransaction();
            $Added = Paper::create([
                "title" => strtolower($request['data']['title']),
                "description" => strtolower($request['data']['description']),
                "type" => strtolower($request['data']['type']),
            ]);

            foreach ($request['data']['body'] as $inner) {


                $AddedQ = QuestionPaper::create([
                    "select" => strtolower($inner['select']),
                    "question" => strtolower($inner['question']),
                    "required" => strtolower($inner['required']),
                    "id_paper" => $Added->id,
                ]);
//dd($AddedQ->id);
                foreach ($inner['options'] as $item) {
                    // ['id_question_poll_form', 'answer'];
                    $Addedop = OptionPaper::create([
                        "value" => strtolower($item['value']),
                        "id_question_paper" => $AddedQ->id,
                    ]);

                }

            }

            DB::commit();
            return MyApp::Json()->dataHandle($Added, "paper");
        } catch (\Exception $e) {

            DB::rollBack();
            throw new \Exception($e->getMessage());
        }


        return MyApp::Json()->errorHandle("paper", "error");//,$prof->getErrorMessage);

    }


    public function update(Request $request)
    {
        if (d3::query()->where("id", $request->id)->exists()) {
            try {
                DB::beginTransaction();
                $p = d3::where("id", $request->id)->first();
                if ($p) {
                    $p->name = strtolower($request->name);
                    $p->address = strtolower($request->address);
                    $p->save();
                }
                DB::commit();
                return MyApp::Json()->dataHandle("edit successfully", "poll");
            } catch (\Exception $e) {

                DB::rollBack();
                throw new \Exception($e->getMessage());
            }

        } else

            return MyApp::Json()->errorHandle("poll", "حدث خطا ما في التعديل ");//,$prof->getErrorMessage);


    }

    public function destroy($id)
    {
        if (d3::query()->where("id", $id)->exists()) {
            try {

                DB::beginTransaction();
                d3::where("id", $id)->delete();
                DB::commit();
                return MyApp::Json()->dataHandle("success", "poll");
            } catch (\Exception $e) {

                DB::rollBack();
                throw new \Exception($e->getMessage());
            }

        } else

            return MyApp::Json()->errorHandle("poll", "حدث خطا ما في الحذف ");//,$prof->getErrorMessage);


    }
}
/*
 * country
 * hasmany city
 * hasmanythrough shop  city  country->with('shops)
 *                                     whithcount
 *         city
 *               shop
 * belongstocity
 *                    employee
 *  * */
