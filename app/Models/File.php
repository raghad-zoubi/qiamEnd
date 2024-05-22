<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $array)
 */
class File extends Model
{
    use HasFactory;

    protected $table="files";
    protected $fillable = [
        'id_content',
        'name',
        'rank',
        'file',
        'id',
    ];
    protected $hidden = [
        'created_at','updated_at','pivot'
    ];


}
