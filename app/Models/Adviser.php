<?php

namespace App\Models;

use http\Env\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $array)
 * @method static whereHas(string $string, \Closure $param)
 * @method static where(string $string, $id)
 * @method static findOrFail(int $advisorId)
 */
class Adviser extends Model
{

    use HasFactory;

    protected $table = "advisers";
    protected $fillable = [
       'name',
        'about',
        'type',
        'photo',
    ];

    protected $hidden = [

    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function date()
    {
        return $this->hasMany(Date::class, 'id_adviser')
            ->with('reserve');
    }
}
