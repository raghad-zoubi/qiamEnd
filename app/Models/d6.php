<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $array)
 */
class d6 extends Model
{
    use HasFactory;
protected $table='answer_poll_forms';
    protected $fillable = ['id_question_poll_form', 'answer'];

    public function questionPollForm()
    {
        return $this->belongsTo(
            d1::class,
            'id_question_poll_form',
            'id');
    }
//    public function ansPollFormUser()
//    {
//        return $this->hasOne(
//            Ans_poll_form_user::class,
//            'id_answer_poll_form',
//            'id');
//    }
    public function rate()
    {
        return $this->HasMany(d7::class, "id_user", "id");
    }

    public function users()
    {
        return $this->belongsToMany(User::class,"favorites","id_online_course","id_user","id","id");
    }



}
