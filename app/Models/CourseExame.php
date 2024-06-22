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
        'created_at','updated_at','pivot'
    ];
    public function onlineCenter(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Online_Center::class,"id_online_center","id")->withDefault();
    }
    public function content(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Content::class,"id_content","id")->withDefault();
    }
    public function exam(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Exame::class,"id_exam","id");


    }


}
