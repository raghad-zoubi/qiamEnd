<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $array)
 */
class d1 extends Model
{


    use HasFactory;

    protected $table = 'question_poll_forms';
    protected $fillable = ['type', 'id_poll_form', 'question', 'kind'];

    public function pollform()
    {
        return $this->belongsTo(
            d2::class,
            'id_poll_form',
            'id');
    }

    public function answerpollform()
    {
        return $this->hasMany(
            d6::class,
            'id_question_poll_form',
            'id');
    }


}
