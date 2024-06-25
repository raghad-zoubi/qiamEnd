<?php

namespace App\Http\Resources;

use App\Models\Appointment;
use App\Models\Bouns;
use App\Models\Child;
use App\Models\User;
use App\MyApplication\Services\CoursesRuleValidation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExameUserCourse extends JsonResource
{
    public function toArray($request): array
    {
        return [

                    "id_exam" => $this["data"]->id,
                    "title" => $this["data"]->title,
                    "description" => $this["data"]->description,
                    "id_content" => $this["content"]->id,
                    "id_online_center" => $this["content"]->id_online_center,
                    "exam" => $this["content"]->exam,
                    "durationExam" => $this["content"]->durationExam,
                    "numberQuestion" => $this["content"]->numberQuestion,
                    "questions" => $this["data"]->questionexamwith->map(function ($question) {
                        return [
                            "id_question" => $question->id,
                            "question" => $question->question,
                            "option" => $question->option->map(function ($option) {
                                return [
                                    "id" => $option->id,
                                    "option" => $option->option,
                                ];
                            }),
                        ];
                    }),



        ];
    }
}
