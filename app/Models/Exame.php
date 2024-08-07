<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $array)
 */
class Exame extends Model
{

    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
"created_at","updated_at"

    ];

    public function questionexamwith()
    {
        return $this->hasMany(Question::class, 'id_exame')->
        with('option');//->limit(1);
    }

    public function coursexam()
    {
        return $this->HasMany(CourseExame::class, "id_exam", "id");
    }



}
