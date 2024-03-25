<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $array)
 * @method static where(string $string, $id)
 */
class Reserve extends Model
{
    use HasFactory;

    protected $table = "reserves";
    protected $fillable = [
        'status',
        'id_date',
        'id_user',
    ];

    protected $hidden = [

    ];

//    public function user()
//    {
//        return $this->belongsTo(User::class, 'id_user');
//    }
//
//
//    public function date()
//    {
//        return $this->belongsTo(Date::class, 'id_date');
//    }
}
