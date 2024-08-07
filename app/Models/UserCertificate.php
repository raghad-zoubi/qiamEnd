<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static selectRaw(string $string)
 */
class UserCertificate extends Model
{

    protected $table ='user_certificate';
    protected $fillable = [  "certificate", "id_booking" ,"id","number"
    ];
    protected $hidden = ["created_at","updated_at"];

    public function book()
    {
        return $this->belongsTo(Booking::class,'id_booking');
    }
}
