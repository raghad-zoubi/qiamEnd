<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $array)
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


}
