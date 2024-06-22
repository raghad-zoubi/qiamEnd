<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $array)
 * @method static find(int $int)
 * @method static whereBelongsTo($id)
 * @method static where(string $string, $id)
 * @method static whereHas(string $string, \Closure $param)
 */
class Paper extends Model
{
    use HasFactory;
    protected $table='papers';
    protected $fillable = [
        'title',
        'description',
        'type',
        'id',
    ];

    protected $hidden = ["created_at","updated_at"];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */



    public function questionpaper()
    {
    return $this->hasMany(QuestionPaper::class, 'id_paper');
            ;
    }
    public function questionpaperwith()
    {
    return $this->hasMany(QuestionPaper::class, 'id_paper')->
    with('optionpaper')
            ;
    }

    public function coursepaper()
    {
        return $this->HasMany(CoursePaper::class, "id_paper", "id");
    }
    public function onlineCenters()
    {
        return $this->belongsToMany(Online_Center::class);
    }


}
