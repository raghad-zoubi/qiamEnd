<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $array)
 * @method static select(string $string, string $string1)
 * @method static where(array $array)
 */
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
'created_at','updated_at','pivot'
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

    } public function trackcontent(){
        return $this->hasMany(TrackContent::class,"id_content","id");

    }
    public function contentExam(){
        return $this->hasMany(Video::class,"id_content","id");
         //   ->with('videoExam');
    }


    public function courseexam()
    {
        return $this->hasMany(CourseExame::class, 'id_content', 'id');
    }

}
