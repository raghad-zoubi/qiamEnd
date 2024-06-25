<?php

namespace App\Http\Resources;

use App\Models\Appointment;
use App\Models\Bouns;
use App\Models\Child;
use App\Models\Reserve;
use App\Models\User;
use App\MyApplication\Services\CoursesRuleValidation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
//
//class DetailsOnlineCopy extends JsonResource
//{
//    public function toArray(Request $request): array
//    {
//
//        $result = [
//
//            'id_online' =>$this->online->id??null,
//            'id_course' => $this->online->id_course ?? null,
//            'examforcourse' => $this->online->exam ?? null,
//            'serial' => $this->online->serial ?? null,
//            'isopen' => $this->online->isopen ?? null,
//            'price' => $this->online->price ?? null,
//
//            // 'poll' => $this->coursepaper->paper->title ?? null,
//             'content' => $this->content2?? null,
//           //  'file' => $this->content2->file?? null,
//           // 'content' => $this->content?? null,
//        ];
//
//        return $result;
//    }
//}



class DetailsOnlineCopy extends JsonResource
{

    public function toArray($request)
    {   $poll=null;
        $form=null;
        foreach ($this->coursepaper as $p){
            if($p->paper->type=='استمارة')
            {$poll=$p->paper->title;}
            else if($p->paper->type=='استبيان')
            {$form=$p->paper->title;}

    }

        return [
            'form' => $form,
            'poll' => $poll,
               'examforcourse' =>  $this->online->exam == '1' ?"  نعم يوجدامتحان" : " لا يوجدامتحان",
                'serial' => $this->online->serial == '1'  ?" نعم متسلسة" : "ليست متسلسلة",
                'price' => $this->online->price,
               'content' => $this->content2->map(function ($content) {
                return [
                    'numberHours' => $content->numberHours,
                    'numberVideos' => $content->numberVideos,
                    'name' => $content->name,
                    'photo' => $content->photo,
                    'exam' => $content->exam,
                    'videoFiles' => $content->video->map(function ($video) {
                        return [
//                            'id' => $video->id,
//                            'id_content' => $video->id_content,
                            'name' => $video->name,
                            'duration' => $video->duration,
                            'poster' => $video->poster,
                            'video' => $video->video,
                            //'rank' => $video->rank,
                        ];
                    }),
                    'pdfFiles' => $content->file->map(function ($file) {
                        return [
//                            'id' => $file->id,
//                            'id_content' => $file->id_content,
                            'name' => $file->name,
                            'file' => $file->file,
                         //   'rank' => $file->rank,
                        ];
                    }),
                    'name_exam' => $content->courseexam->isNotEmpty() ?
                     $content->courseexam->first()->exam->title : null,


                ];
            }),

        ];
    }
}
