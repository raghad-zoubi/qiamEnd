<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    public $table = "notifications";
    public $primaryKey = 'id';
    public $fillable = [
        'type', 'notifiable', 'data', 'read_at','created_at','updated_at',
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
