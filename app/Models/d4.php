<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $array)
 * @method static where(string $string, $id)
 */
class d4 extends Model
{
    use HasFactory;
    protected $table="forms";
    protected $fillable = [
        'address',
        'name',
        'id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [

    ];

    public function pollForms()
    {
        return $this->hasMany(d2::class, 'id_form');
    }

    public function onlinecourses(){
        return $this->hasMany(Online::class,"id_form","id");
    }
    public function centercourses(){
        return $this->hasMany(Center::class,"id_course","id");
    }
}
