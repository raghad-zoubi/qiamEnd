<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{

    protected $table="videos";
    protected $fillable = [
        'id_content',
        'name',
        'rank',
        'file',
        'duration',
        'id',
    ];

    protected $hidden = [

    ];

}
