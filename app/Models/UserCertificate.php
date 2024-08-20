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
    protected $fillable = [  "certificate", "id_user", "id_online_center" ,"id","number","created_at","updated_at"
    ];
    protected $hidden = [];

    public function certificate(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            Online_Center::class,"id_online_center","id")->withDefault();
    }
    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class,"id_user","id")->withDefault();
    }


}
