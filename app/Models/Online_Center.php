<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Online_Center extends Model
{
    use HasFactory;
    protected $table = "online_centers";

    protected $fillable = [   "id_online_course", "id_center_course" ,"id"
        ,"id_course"
    ];
    protected $hidden = ["created_at","updated_at"];

    public function favorite()
    {
        return $this->HasMany(Favorite::class, "id_user", "id");
    }
    public function rate()
    {
        return $this->HasMany(Rate::class, "id_user", "id");
    }

    public function users()
    {
        return $this->belongsToMany(User::class,"favorites","id_online_course","id_user","id","id");
    }
//  public function users()
//    {
//        return $this->belongsToMany(User::class,"rates","id_online_course","id_user","id","id");
//    }


}
