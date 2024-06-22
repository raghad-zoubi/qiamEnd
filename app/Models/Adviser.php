<?php

namespace App\Models;

use Carbon\Carbon;
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
        "created_at",
                    "updated_at","pivot"
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function date()
   {  //$date = Carbon::now();  $d->format("Y-m-d")
//        $d=$date->addDays(5);
       //  dd(      );
        return $this->hasMany(Date::class, 'id_adviser','id');
//          ->where('day', '<',  $d->format("Y-m-d"))//  ->with('reserve');
//          ->where('day', '>',  $date->format("Y-m-d"));//  ->with('reserve');
    }  public function date2()
   {  //$date = Carbon::now();  $d->format("Y-m-d")
//        $d=$date->addDays(5);
       //  dd(      );
        return $this->hasMany(Date::class, 'id_adviser','id')
            ->with('reserve');
//          ->where('day', '<',  $d->format("Y-m-d"))//  ->with('reserve');
//          ->where('day', '>',  $date->format("Y-m-d"));//  ->with('reserve');
    }
}
