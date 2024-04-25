<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(array $array)
 * @method static create(array $array)
 * @method static whereDoesntHave(string $string, \Closure $param)
 * @method static whereHas(string $string, \Closure $param)
 */
class Booking extends Model
{
    use HasFactory;

    protected $table ='booking';
    protected $fillable = [   "id_online_center", "id_user" ,"id","mark","status",'done'
    ];
    protected $hidden = ["created_at","updated_at"];


    public function booking(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Online_Center::class,"id_online_center","id")->withDefault();
    }
    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class,"id_user","id")->withDefault();
    }

}

