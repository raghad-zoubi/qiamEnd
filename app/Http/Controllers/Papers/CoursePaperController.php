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
           $questionsWithOptions = Paper::
            with('questionpaperwith'
            )->whereHas('coursepaper', function ($query) use ($id_online_center) {
                $query->where('id_online_center', $id_online_center);
            })->
            get();
            $optionsWithanswe = AnswerPaper::query()->
           where('id_user', $id_user)->
            get();

            $mergedData = [];

            foreach ($questionsWithOptions as $question) {
                $mergedItem = $question->toArray();
                $mergedItem['optionpaper'] = [];

//
//                foreach ($optionsWithanswe as $option) {
//                    if ($option->id_question_paper === $question->id) {
//                        $mergedItem['optionpaper'][] = $option->toArray();
//                    }
//                }

              //  $mergedData[] = $mergedItem;
            }

// Now $mergedData contains the merged data with each question's options


            DB::commit();

            //return MyApp::Json()->dataHandle($optionsWithanswe, "paper");
            return response()->json([

            'questionsWithOptions' ,$questionsWithOptions,
                //       //     'mergedData' ,$mergedData,
              'optionsWithanswe' ,$optionsWithanswe,
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
