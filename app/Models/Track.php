<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Track extends Model
{
    use HasFactory;


    protected $table="tracks";
    protected $fillable = [
        'id_video',
        'id_booking',
        'id',
        'endTime',
        'done',
    ];

    protected $hidden = ["created_at","updated_at"];

    public function booking(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Booking::class,"id_booking","id")->withDefault();
    }
    public function video(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Video::class,"id_video","id");}

    public function videoforcontent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Video::class,"id_video","id")->with('content');}


}
