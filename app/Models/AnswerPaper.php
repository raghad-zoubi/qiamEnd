<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(string[] $array)
 */
class AnswerPaper extends Model
{

    use HasFactory;
    protected $fillable = ['id_question_paper','id_option_paper', 'id_user', 'ans'];

    public function optionpaper()
    {
        return $this->belongsTo(
                OptionPaper::class,
                'id_option_paper',
                'id');

    }
    public function questionpaper()
    {
        return $this->belongsTo(QuestionPaper::class);
    }

    public function user()
    {
        return $this->belongsTo(
            User::class
        );
    }


}

