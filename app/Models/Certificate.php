<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static findOrFail($id)
 */
class Certificate extends Model
{
    use HasFactory;

    protected $table="certificates";
    protected $fillable = [
        'id',
        'photo'

    ];

    protected $hidden = [

    ];
}
