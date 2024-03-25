<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $array)
 * @method static where(string $string, $id)
 */
class Cours extends Model
{

    use HasFactory;
    protected $table="courses";
    protected $fillable = [
        'name',
        'about',
        'path',
        'id',
    ];

    protected $hidden = [

    ];

    public function onlinecourses(){
        return $this->hasMany(Online::class,"id_course","id");
    }   public function centercourses(){
        return $this->hasMany(Center::class,"id_course","id");
    }
}
