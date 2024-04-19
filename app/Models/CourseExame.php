<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $array)
 */
class CourseExame extends Model
{
    protected $table="course_exames";
    protected $fillable = [
        'id_exam',
        'id_online_center',
        'id_content',

        'id',
    ];

    protected $hidden = [

    ];

}
