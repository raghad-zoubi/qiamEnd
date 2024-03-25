<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class d7 extends Model
{
    use HasFactory;
    protected $fillable = ['id_answer_poll_form', 'id_user', 'ans'];

    public function ans_poll_form()
    {
        return $this->belongsTo(
            d6::class,
            'id_answer_poll_form',
             'id')>withDefault();

}
    public function user()
    {
        return $this->belongsTo(
            User::class,
            'id_user',
            'id_user'
        );
    }


}
