<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $array)
 * @method static select(string[] $array)
 */
class QuestionPaper extends Model
{

    use HasFactory;

    protected $table = 'question_papers';
    protected $fillable = ['select', 'id_paper', 'question', 'required','id'];

    protected $hidden = ["created_at","updated_at"];



    public function optionpaper()
    {
        return $this->hasMany(
            OptionPaper::class,'id_question_paper','id');
    }  public function optionpaperwithUser()
    {
        return $this->hasMany(
            OptionPaper::class,'id_question_paper','id');
    }
    public function paper()
    {
        return $this->belongsTo(Paper::class, 'id_paper'); // 'id_paper' is the correct foreign key
    }



}
