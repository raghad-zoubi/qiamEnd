<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrackContent extends Model
{

    use HasFactory;
    protected $table="track_contents";
    protected $fillable = [
        'id',
        'id_booking',
        'id_content'
    ];

    protected $hidden = [
        'created_at','updated_at',
    ];
    public function content()
    {
        return $this->belongsTo(Content::class, "id_content", "id");
    }


}
