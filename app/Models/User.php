<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;
use Laravel\Sanctum\HasApiTokens;

/**
 * @method static where(string $string, $email)
 * @method static create(array $array)
 * @method static whereHas(string $string, \Closure $param)
 */
class User extends Authenticatable   implements  MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;
    //, Queueable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'id_profile',
         'active_code',
        'code',
        'email_verified_at',
        'fcm_token',
        'token'
    ];




    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',

    ];

    public static function GenerateCode(): int
    {
        $code = random_int(10000, 99999);
        $temp = $code;
        return $temp;
    }
    public static function SendCodeToEmailActive($mail_data)
    {

        Mail::send('email-template', $mail_data, function ($message) use ($mail_data) {
            $message->to($mail_data['recipient'])
                ->from($mail_data['fromEmail'],
                    $mail_data['fromName'])
                ->subject($mail_data['subject']);
         //   dd("send");
        });
    }
//
public function favorite()
{
    return $this->HasMany(Favorite::class, "id_user", "id");
}public function reserve()
{
    return $this->HasMany(Reserve::class, "id_user", "id");
}
public function rate()
{
    return $this->HasMany(Rate::class, "id_user", "id");
}public function booking()
{
    return $this->HasMany(Booking::class, "id_user", "id");
}
public function answerpollform()
{
    return $this->HasMany(d6::class, "id_user", "id");
}
public function answer()
{
    return $this->HasMany(Answer::class, "id_user", "id");
}

//------------------------------

    public function profile()
    {
        return $this->hasOne(Profile::class, 'id_user','id');
    }

    public function adviser(){
        return $this->hasOne(Adviser::class,"id_user","id");
    }

//------------------------------

//----------------------
    public function dates(){
        return $this->belongsToMany(Date::class,
            "reserves",
            "id_user",
          "id_date",
           "id");
    }

}
//composer require laravel/sanctum
//php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
//php artisan migrate

