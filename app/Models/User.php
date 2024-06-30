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
 * @method static selectRaw(string $string)
 */
use Illuminate\Database\Eloquent\Model;


/**
 * @method static where(string $string, int $int)
 */
class User extends Authenticatable   implements  MustVerifyEmail
{    use \Illuminate\Auth\Authenticatable;

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
        'token',
        'role',
        'remember_token',
    ];




    protected $hidden = [
    //    'password',
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
    public function pushNotification($title,$body,$message){

        $token = $this->fcm_token;


        if($token == null) {
            return 0;}

        $data['notification']['title']= $title;
        $data['notification']['body']= $body;
        $data['notification']['sound']= true;
        $data['priority']= 'normal';
        $data['data']['click_action'] = 'FLUTTER_NOTIFICATION_CLICK';
        $data['data']['message']=$message;
        $data['to'] = $token;


        $http = new \GuzzleHttp\Client(['headers'=>[
            'Centent-Type'=>'application/json',
            'Authorization'=>'key=AAAAuWiet7w:APA91bFMtMwvQJHHYe7VBzAMCy5wBRqRDyAXmnooA2Tpn2X0Tap9_o5WWvTuceJAsHDehnEWA2CZHpQ6jF65jg0sfn3mnfIRsk87lz0CeC4eNBh482pUkFrH_mCoEpWualUyvderE8Za'

        ]]);
        try {
            $response = $http->post('https://fcm.googleapis.com/fcm/send', [ 'json' =>
                $data
            ]);
            return $response->getBody();
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            // return $e->getCode();
            if ($e->getCode() === 400) {
                return response()->json(['ok'=>'0', 'erro'=> 'Invalid Request.'], $e->getCode());
            } else if ($e->getCode() === 401) {
                return response()->json('Your credentials are incorrect. Please try again', $e->getCode());
            }
            return response()->json('Something went wrong on the server.', $e->getCode());
        }

    }



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


    /////////////////
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

