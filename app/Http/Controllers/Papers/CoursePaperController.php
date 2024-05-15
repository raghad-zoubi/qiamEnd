<?php

namespace App\Http\Controllers\Papers;

use App\Http\Controllers\Controller;
use App\Models\AnswerPaper;
use App\Models\CoursePaper;
use App\Models\OptionPaper;
use App\Models\Paper;
use App\Models\QuestionPaper;
use App\MyApplication\MyApp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

// user
class CoursePaperController extends Controller
{

    public function displayPaperUser($id_user, $id_online_center)
    {
//dd("dd");

        try {
          DB::beginTransaction();
//           $questionsWithOptions = Paper::
//            with('questionpaperwith'
//            )->whereHas('coursepaper', function ($query) use ($id_online_center) {
//                $query->where('id_online_center', $id_online_center);
//            })->
//            get();
//            $optionsWithanswe = AnswerPaper::query()->
//           where('id_user', $id_user)->
//            get();

//            $questionPapers = QuestionPaper::query()
//                ->join('papers', 'papers.id', '=', 'question_papers.id_paper')
//                ->join('course_papers', 'course_papers.id_paper', '=', 'papers.id')
//                ->join('online_centers', 'online_centers.id', '=', 'course_papers.id_online_center')
//                ->where('online_centers.id', 2)
//                ->select('question_papers.*')
//                ->get();
//
//            $options = OptionPaper::query()
//                ->join('question_papers', 'question_papers.id', '=', 'option_papers.id_question_paper')
//                ->join('papers', 'papers.id', '=', 'question_papers.id_paper')
//                ->join('course_papers', 'course_papers.id_paper', '=', 'papers.id')
//                ->join('online_centers', 'online_centers.id', '=', 'course_papers.id_online_center')
//                ->where('online_centers.id', 2)
//                ->select('option_papers.*')
//                ->get();
//
//            $answers = AnswerPaper::query()
//                ->join('question_papers', 'question_papers.id', '=', 'answer_papers.id_question_paper')
//                ->join('papers', 'papers.id', '=', 'question_papers.id_paper')
//                ->join('course_papers', 'course_papers.id_paper', '=', 'papers.id')
//                ->join('online_centers', 'online_centers.id', '=', 'course_papers.id_online_center')
//                ->where('online_centers.id', 2)
//                ->where('answer_papers.id_user', 1)
//                ->select('answer_papers.*')
//                ->get();
//
//
            $data = DB::table('question_papers')
                ->select(
                    'question_papers.*',
                    'papers.*',
                    'option_papers.*',
                    'answer_papers.*'
                )
                ->join('papers', 'papers.id', '=', 'question_papers.id_paper')
                ->leftJoin('option_papers', 'option_papers.id_question_paper', '=', 'question_papers.id')
                ->leftJoin('answer_papers', 'answer_papers.id_question_paper', '=', 'question_papers.id')
                ->join('course_papers', 'course_papers.id_paper', '=', 'papers.id')
                ->join('online_centers', 'online_centers.id', '=', 'course_papers.id_online_center')
                ->where('online_centers.id', 2)
                ->where('answer_papers.id_user', 11)
                ->get();

            DB::commit();

            //return MyApp::Json()->dataHandle($optionsWithanswe, "paper");
            return response()->json([
'data',$data
//            'questionsWithOptions' ,$questionsWithOptions,
//                //       //     'mergedData' ,$mergedData,
//              'optionsWithanswe' ,$optionsWithanswe,
            ]);
        } catch (\Exception $e) {

            DB::rollBack();
            throw new \Exception($e->getMessage());
        }


        return MyApp::Json()->errorHandle("paper", "لقد حدث خطا ما اعد المحاولة لاحقا");//,$prof->getErrorMessage);

        // AnswerPaper
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //dd("dd");

        try {
            DB::beginTransaction();
            $questionsWithOptions = Paper::

            with('questionpaperwith'
            )->whereHas('coursepaper', function ($query) use ($id) {
                $query->where('id_online_center', $id);
            })->
            get();


            DB::commit();

            return MyApp::Json()->dataHandle($questionsWithOptions, "paper");
        } catch (\Exception $e) {

            DB::rollBack();
            throw new \Exception($e->getMessage());
        }


        return MyApp::Json()->errorHandle("paper", "لقد حدث خطا ما اعد المحاولة لاحقا");//,$prof->getErrorMessage);

    }

    public function answer(Request $request)
    {

        try {
            DB::beginTransaction();

            foreach ($request->options as $item) {
                AnswerPaper::create([
                    "id_user" => auth()->id(),
                    "answer" => $item['answer'],
                    "id_question_paper" => $item['id_question_paper'],
                    "id_option_paper" => $item['id_option_paper']
                ]);
            }


            DB::commit();

            return MyApp::Json()->dataHandle('correct', "data");
        } catch (\Exception $e) {

            DB::rollBack();
            throw new \Exception($e->getMessage());
        }


        return MyApp::Json()->errorHandle("paper", "لقد حدث خطا ما اعد المحاولة لاحقا");//,$prof->getErrorMessage);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CoursePaper $course_Paper)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CoursePaper $course_Paper)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CoursePaper $course_Paper)
    {
        //
    }
}
