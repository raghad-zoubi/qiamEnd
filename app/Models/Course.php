<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $array)
 * @method static where(string $string, $id)
 * @method static find($id)
 */

class Course extends Model
{

    use HasFactory;
    protected $table="courses";
    protected $fillable = [
        'name',
        'about',
        'photo',
        'id',
        'teacher',
        'text',
    ];

    protected $hidden = [

    ];

    public function online(){
        return $this->hasMany(Online::class,"id_course","id");
    }   public function center(){
        return $this->hasMany(Center::class,"id_course","id")
       ;// ->select(["price","id"])->withDefault();
        //->where("Center.start");
}
    public function onlineCenters()
    {
        return $this->hasMany(Online_Center::class, 'id_course', 'id');
    }

}
