<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static findOrFail(int $int)
 */
class Video extends Model
{

    protected $table="videos";
    protected $fillable = [
        'id_content',
        'name',
        'rank',
        'video',
        'poster',
        'duration',
        'id',
    ];
    protected $hidden = [
        'created_at','updated_at','pivot'
    ]; public function content()
{
    return $this->belongsTo(Content::class, "id_content", "id");
}
}





