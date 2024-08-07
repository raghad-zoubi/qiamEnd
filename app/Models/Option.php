<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $array)
 */
class Option extends Model

{
    use HasFactory;
    protected $fillable = ['id_question', 'option','correct'];
    protected $hidden = ["created_at","updated_at"];

    public function question()
    {
        return $this->belongsTo(
            QuestionPaper::class,
            'id_question',
            'id');
    }

//    public function rate()
//    {
//        return $this->HasMany(d7::class, "id_user", "id");
//    }
//
//    public function users()
//    {
//        return $this->belongsToMany(User::class,"favorites","id_online_course","id_user","id","id");
//    }





}
