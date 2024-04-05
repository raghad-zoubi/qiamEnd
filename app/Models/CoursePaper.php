<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoursePaper extends Model
{
    protected $table="course_papers";
    protected $fillable = [
        'id_online_center',
        'id_paper',
        'id',
    ];

    protected $hidden = [

    ];

}
