<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static findOrFail($id)
 * @method static orderBy(string $string, string $string1)
 * @method static latest()
 */
class Certificate extends Model
{
    use HasFactory;

    protected $table="certificates";
    protected $fillable = [
        'id',
        'photo',
        'created_at'

    ];

    protected $hidden = [

    ];
}
