<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $array)
 * @method static where(string $string, $id)
 * @method static whereHas(string $string, \Closure $param)
 * @method static doesntHave(string $string)
 */
class Date extends Model
{

    use HasFactory;

    protected $table = "dates";
    protected $fillable = [
        'from',
        'to',
        'day',
        'id_adviser',
    ];

    protected $hidden = [

    ];

    public function adviser()
    {
        return $this->belongsTo(Adviser::class, 'id_adviser','id');
    }

    public function reserve()
    {
        return $this->HasMany(Reserve::class, "id_date", "id");
    }

    public function users()
    {
        return $this->belongsToMany(User::class)
            ;//->
      //  withPivot('id', 'created_by');
    }


}
