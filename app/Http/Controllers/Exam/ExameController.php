<?php

namespace App\Http\Controllers\Exam;

use App\Http\Controllers\Controller;
use App\Models\d3;
use App\Models\Exame;
use App\Models\Option;
use App\Models\OptionPaper;
use App\Models\Paper;
use App\Models\Question;
use App\Models\QuestionPaper;
use App\MyApplication\MyApp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExameController extends Controller
{
    public function __construct()
    {
        //  $this->middleware(["auth:sanctum"]);
        //     $this->rules = new PaperRuleValidation();
    }

    public function index()
    {

        try {
            DB::beginTransaction();
            $examget = Exame::query()->get();
            DB::commit();
            return MyApp::Json()->dataHandle($examget, "exam");
        } catch (\Exception $e) {

            DB::rollBack();
            throw new \Exception($e->getMessage());
        }


        return MyApp::Json()->errorHandle("exam", "لقد حدث خطا ما اعد المحاولة لاحقا");//,$prof->getErrorMessage);

    }
    public function show($id)
    {

        try {
            DB::beginTransaction();
            $environments = Question::query()->with('option')
                ->where('id_exame',$id)->get();
            DB::commit();
            return MyApp::Json()->dataHandle($environments, "exam");
        } catch (\Exception $e) {

            DB::rollBack();
            throw new \Exception($e->getMessage());
        }


        return MyApp::Json()->errorHandle("exam", "لقد حدث خطا ما اعد المحاولة لاحقا");//,$prof->getErrorMessage);

    }

    public function Create(Request $request)
    {
        //      return MyApp::Json()->dataHandle($request['body'][0]['question'], "poll");


        //protected $fillable = ['type', 'id_poll_form', 'question', 'kind'];

        //  $request->validate($this->rules->onlyKey(["name","address"],true));
        try {
            DB::beginTransaction();
            $Added = Exame::create([
                "title" => strtolower($request['title']),
                "description" => strtolower($request['description']),
            ]);

            foreach ($request['body'] as $inner) {


                $AddedQ = Question::create([
                    "question" => strtolower($inner['question']),
                    "id_exame" => $Added->id,
                ]);
                foreach ($inner['options'] as $item) {
                    $Addedop = Option::create([
                        "correct" => strtolower($item['correct']),
                        "option" => strtolower($item['option']),
                        "id_question" => $AddedQ->id,
                    ]);
                }}
            DB::commit();
            return MyApp::Json()->dataHandle($Added, "Exam");
        } catch (\Exception $e) {

            DB::rollBack();
            throw new \Exception($e->getMessage());
        }


        return MyApp::Json()->errorHandle("Exam", "error");//,$prof->getErrorMessage);

    }

    public function delete($id)
    {
        if (Exame::query()->where("id", $id)->exists()) {
            try {

                DB::beginTransaction();
                Exame::where("id", $id)->delete();
                DB::commit();
                return MyApp::Json()->dataHandle("success", "exam");
            } catch (\Exception $e) {

                DB::rollBack();
                throw new \Exception($e->getMessage());
            }

        } else

            return MyApp::Json()->errorHandle("exam", "حدث خطا ما في الحذف ");//,$prof->getErrorMessage);


    }

}
