<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $array)
 * @method static where(string $string, int|string|null $id)
 */
class Profile extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'id_user',
        'lastName',
        'fatherName',
        'gender',
        'birthDate',
        'mobilePhone',
        'specialization',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function user()
    {
        return $this->belongsTo(User::class,
            'id_user','id');
        }
}
