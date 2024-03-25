<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $array)
 */
class d2 extends Model
{
    use HasFactory;
    protected $table='poll_forms';
    protected $fillable = ['id_poll', 'id_form'];

    public function poll()
    {
        return $this->belongsTo(d3::class, 'id_poll');
    }

    public function form()
    {
        return $this->belongsTo(d4::class, 'id_form');
    }
    public function qusetionPollForm(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(d1::class,'id_poll_form',
            'id');
    }
}
