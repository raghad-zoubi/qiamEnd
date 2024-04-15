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



    public function reserve(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Date::class,"id_date","id")->withDefault();
    }
    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class,"id_user","id")->withDefault();
    }


}
