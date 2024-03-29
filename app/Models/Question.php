<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $array)
 * @method static where(string $string, $id)
 */
class Question extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_exame',
        'question',
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

    public function option()
    {
        return $this->hasMany(
            Option::class,'id_question','id');
    }
}
