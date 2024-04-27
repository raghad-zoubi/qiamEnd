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
    protected $fillable = [   "id_certificate", "id_booking" ,"id","mark"
    ];
    protected $hidden = ["created_at","updated_at"];


}
