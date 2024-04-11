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
    public function OnlineCenter()
    {
        return $this->belongsTo(Online_Center::class, "id_online_center", "id")
            ;
    }
    public function file(){
        return $this->hasMany(File::class,"id_content","id");

    }
    public function video(){
        return $this->hasMany(Video::class,"id_content","id");

    }
}
