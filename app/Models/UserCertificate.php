<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static selectRaw(string $string)
 * @method static orderBy(string $string, string $string1)
 * @method static whereHas(string $string, \Closure $param)
 */
class UserCertificate extends Model
{

    protected $table ='user_certificate';
    protected $fillable = [  "certificate", "id_booking" ,"id","number","created_at","updated_at"
    ];
    protected $hidden = [];

    public function book()
    {
        return $this->belongsTo(Booking::class,'id_booking');
    }
    public function book2()
    {
        return $this->belongsTo(Booking::class,'id_booking')
            ->with(['users','booking2']);
    }
}
