<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $array)
 */
class OptionPaper extends Model
{
    use HasFactory;
protected $table='option_papers';
    protected $fillable = ['id_question_paper', 'value','id'];

    public function questionpaper()
    {
        return $this->belongsTo(QuestionPaper::class);
    }


//    public function ansPollFormUser()
//    {
//        return $this->hasOne(
//            Ans_poll_form_user::class,
//            'id_answer_poll_form',
//            'id');
//    }

    public function users()
    {
        return $this->belongsToMany(User::class,
        );
    }
    public function paper()
    {
        return $this->belongsToMany(Paper::class,QuestionPaper::class);
    }



}
