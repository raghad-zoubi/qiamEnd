<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnswerPaper extends Model
{

    use HasFactory;
    protected $fillable = ['id_option_paper', 'id_user', 'ans'];

    public function optionpaper()
    {
        return $this->belongsTo(
                OptionPaper::class,
                'id_option_paper',
                'id');

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

