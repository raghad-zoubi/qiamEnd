<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * @method static create(array $array)
 * @method static leftJoin(string $string, string $string1, string $string2, string $string3)
 * @method static joinSub($ratesSubquery, string $string, \Closure $param)
 * @method static where(string $string, string $string1, int $int)
 */
class Online_Center extends Model
{
    use HasFactory;
    protected $table = "online_centers";

    protected $fillable = [
        "id_online",
        "id_center" ,
        "id",
        "id_course"
    ];
    protected $hidden = ["created_at","updated_at"];

    public function certificate()
    {
        return $this->HasMany(UserCertificate::class, "id_user", "id");
    }
    public function favorite()
    {
        return $this->HasMany(Favorite::class, "id_user", "id");
    }
    public function rate()
    {
        return $this->HasMany(Rate::class, "id_user", "id")
            ;
    }
    public function booking()
    {
        return $this->HasMany(Booking::class, "id_user", "id")
            ;
    }
    public function users()
    {
        return $this->belongsToMany(User::class,"favorites","id_online_course","id_user","id","id");
    }
    public function center()
    {
        return $this->belongsTo(Center::class, 'id_center', 'id')
            ;// ->where ('end','>',Carbon::now());
    }
    public function online()
    {
        return $this->belongsTo(Online::class, 'id_online', 'id')

            ;
    }    public function onlinehome()
{
    return $this->belongsTo(Online::class, 'id_online', 'id')->
    where('isopen','=','1');

}

    public function course()
    {
        return $this->belongsTo(Course::class, 'id_course', 'id');
    }

    public function course2()
    {
        return $this->belongsTo(Course::class, 'id_course', 'id')->with('online');
    }
    public function content(){
        return $this->hasMany(Content::class,"id_online_center","id")
            ;// ->with(['file','video']);

    }
    public function content2(){
        return $this->hasMany(Content::class,"id_online_center","id")
            ->with(['file','video','courseexam.exam']);

    }
    public function coursepaper(){
        return $this->hasMany(CoursePaper::class,"id_online_center","id")
            ;// ->with(['file','video']);

    }

    public function papers()
    {
        return $this->belongsToMany(Paper::class);
    }

}
