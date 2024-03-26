<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $array)
 * @method static find(int $int)
 * @method static whereBelongsTo($id)
 * @method static where(string $string, $id)
 */
class Paper extends Model
{
    use HasFactory;
    protected $table='papers';
    protected $fillable = [
        'title',
        'description',
        'type',
        'id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [

    ];


//    public function onlinecours()
//    {
//        return $this->belongsTo(OnlineCours::class);
//
//    }
//    public function onlinecourses(){
//        return $this->hasMany(Online::class,"id_poll","id");
//    }
//    public function centercourses(){
//        return $this->hasMany(Center::class,"id_course","id");
//    }
    public function questionpaper()
    {
        return $this->hasMany(QuestionPaper::class)
           // ->with('optionpaper')
            ;
    }

//    public function optionQuestion()
//    {
//        return $this->hasManyThrough(OptionPaper::class, QuestionPaper::class);
//    }
}
