<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    use HasFactory;
    protected $table="contents";
    protected $fillable = [
        'id_online_center',
        'numberHours',
        'numberVideos',
        'durationExam',
        'numberQuestion',
        'name',
        'rank',
        'exam',
        'photo',
        'id',
    ];

    protected $hidden = [

    ];

}
