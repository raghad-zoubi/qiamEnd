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
    /**
     * Display a listing of the resource.
     */

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
            $examget = Exame::query()->get();
            DB::commit();
            return MyApp::Json()->dataHandle($examget, "exam");
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
                "title" => strtolower($request['data']['title']),
                "description" => strtolower($request['data']['description']),
            ]);

            foreach ($request['data']['body'] as $inner) {


                $AddedQ = Question::create([
                    "question" => strtolower($inner['question']),
                    "id_exame" => $Added->id,
                ]);
//dd($AddedQ->id);
                foreach ($inner['options'] as $item) {
                    // ['id_question_poll_form', 'answer'];
                    $Addedop= Option::create([
                        "correct" => strtolower($item['correct']),
                        "option" => strtolower($item['option']),
                        "id_question" => $AddedQ->id,
                    ]);

                }

            }

            DB::commit();
            return MyApp::Json()->dataHandle($Added, "Exam");
        } catch (\Exception $e) {

            DB::rollBack();
            throw new \Exception($e->getMessage());
        }


        return MyApp::Json()->errorHandle("Exam", "error");//,$prof->getErrorMessage);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Exame $exame)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Exame $exame)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Exame $exame)
    {
        //
    }
}
