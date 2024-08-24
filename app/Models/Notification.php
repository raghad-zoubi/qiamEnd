<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $array)
 */
class Notification extends Model
{
    use HasFactory;

    public $table = "notifications";
    public $primaryKey = 'id';
    public $fillable = [
        'body', 'title', 'id_user','created_at','updated_at',
    ];

    public $timestamps = true;

    protected $hidden = [
        'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }




}
