<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReExam extends Model
{
    use HasFactory;


    protected $table ='re_exams';
    protected $fillable = [  "id_user", "id_online_center" ,"id","status","created_at","updated_at"
    ];
    protected $hidden = [];

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class,"id_user","id")
            ->with('profile')->select();
    }

    public function booking(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Online_Center::class,"id_online_center","id")->withDefault();
    }
}
