<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, $id_online_center)
 * @method static create(array $array)
 */
class CoursePaper extends Model
{
    protected $table="course_papers";
    protected $fillable = [
        'id_online_center',
        'id_paper',
        'id',
    ];

    protected $hidden = ["created_at","updated_at"];

    public function onlineCenter(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Online_Center::class,"id_online_center","id")->withDefault();
    }
    public function paper(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Paper::class,"id_paper","id");}

}
