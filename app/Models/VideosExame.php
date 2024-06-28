<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $array)
 */
class VideosExame extends Model
{
    protected $table="video_exames";
    protected $fillable = [
        'id_exam',
        'id_vedio',
        'id',
    ];

    protected $hidden = [
        'created_at','updated_at','pivot'
    ];
    public function exam(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Exame::class,"id_exam","id");


    }public function video(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Video::class,"id_vedio","id");


    }


}
