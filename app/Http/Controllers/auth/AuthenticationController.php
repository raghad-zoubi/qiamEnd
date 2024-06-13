<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\MyApplication\MyApp;
use App\MyApplication\RuleValidate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
class AuthenticationController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum')->only(["logout"]);
    }

    public function register(Request $request): \Illuminate\Http\JsonResponse
    {

        $validate = Validator::make($request->all(), [
           // "name" => ["required", "string", "alpha", "max:255"],
            "email" => ["required", Rule::unique("users", "email"), "email"],
            "role" => ["required","string"],
            //Rule::unique("users", "phone"), new PhoneNumber()],
            "password" => ["required", "min:8", "string"]//, "confirmed"],

        ]);

        if ($validate->fails()) {
            return Response()->json([
                "status" => "failure",
                "message" => $validate->errors()->all()[0]
            ]);
        }


        DB::beginTransaction();
        try {
            $code = User::GenerateCode();
            $user = User::create([
                "email_verified_at" => Carbon::now(),
                "email" => $request->email,
            //    "name" => $request->name,
                "role" => '2',
                "code" => password_hash($code, PASSWORD_DEFAULT),
                "password" => password_hash($request->password, PASSWORD_DEFAULT),
            ]);
            $data = str_split($code);
            /*  if (!$this->isOnlineInternet()) {
                  return \response()->json([
                      "Error" => "no internet",
                      "status" => "failure",
                  ], 200);
              }*/
            $mail_data = [
                'recipient' => $request->email,
                'fromEmail' => "raghad.alzoubi.2001@gmaail.com",
                'fromName' => "Alkeyam company",
                'subject' => "Alkeyam App:Email verification ",
                'data' => $data,
                "email" => $request->email,
            ];

            User::SendCodeToEmailActive($mail_data);
            //   $token = $user->createToken($request->name, ["*"])->plainTextToken;

            DB::commit();
            return Response()->json([
                "user" => $user,
                "status" =>"success" ,
                "message"=>"user signup successfuly"

            ], );
        } catch (\Exception $exception) {
            DB::rollBack();
            return \response()->json([
                "status" => "failure",
                "message" => $exception->getMessage()
            ]);
        }
    }
    public function login(Request $request): \Illuminate\Http\JsonResponse
    {
        $validate = Validator::make($request->all(), [
            "email" => ["required", Rule::exists("users", "email")],
            "password" => ["required","min:8", "string"],
            "fcm_token"=>["required","min:10","string"]
        ]);
        if ($validate->fails()) {
            return Response()->json([
                "status" => "failure",
                "message" => $validate->errors()->all()[0]
            ]);
        }
        $user = User::where("email", $request->email)->first();
        if (password_verify($request->password, $user->password)) {
            if ($user->active_code === true || $user->active_code == 1) {
                $token = $user->createToken($user->email, ["*"])->plainTextToken;
                $user->update(["fcm_token"=>$request->fcm_token,
                    "token"=>$token]);
                return Response()->json([
                    "status" => "success",
                    "user" => $user,
                    "token" => $token,
                    "active" => true,
                    "message" => "email is  active!"
                ]);
            } else {
                $x=   $this->resendActiveEmail($request);
                return Response()->json([
                    "status" => "success",
                    "active" => false,
                    "message" => "email is not active!"
                ]);
            }
        } else {
            return Response()->json([
                "message" => "password is error!!",
                "status" => "failure",
            ]);
        }

    }

    public function resendActiveEmail(Request $request): \Illuminate\Http\JsonResponse
    {
        $validate = Validator::make($request->all(), [
            "email" => ["required", Rule::exists("users", "email")],
        ]);
        if ($validate->fails()) {
            return \response()->json([
                "status" => "failure",
                "message" => $validate->errors()->all()[0]
            ]);
        }

        $user = User::where("email", $request->email)->first();
        if ($user->active_code == false) {
            $code = User::GenerateCode();
            $user->update([
                "code" => password_hash($code, PASSWORD_DEFAULT),
            ]);
            $data = str_split($code);
            /* if (!$this->isOnlineInternet()) {
                 return \response()->json([
                     "Error" => "no internet",
                     "status" => "failure",
                 ], 200);
             }*/
            $mail_data = [
                'recipient' => $request->email,
                'fromEmail' => "raghad.zy2017@gmaail.com",
                'fromName' => "AAlkeyam company",
                'subject' => "Alkeyam App:Email verification ",
                'data' => $data,
                "name" => $request->name,
                "email" => $request->email,
            ];

            User::SendCodeToEmailActive($mail_data);
            return Response()->json([
                "message" => "resend mail",
                "status" => "success"
            ]);
        }else{
            return Response()->json([
                "message" => "you are viry",
                "status" => "success"
            ]);


        }
    }


    public function logout(): \Illuminate\Http\JsonResponse
    {


        try {
            DB::beginTransaction();
            $user = auth()->user();
            $user->currentAccessToken()->delete();
            DB::commit();
            return response()->json(["Message" => "Successfully logged out", "status" => "success"], 201);
        } catch (\Exception $exception) {
            return response()->json([
                "status" => "failure",
                "message" => $exception->getMessage()
            ]);
        }
    }

    public function isOnlineInternet($site = "www.google.com"): bool
    {
        if (@fopen($site, "r")) {
            return true;
        } else {
            return false;
        }
    }



    public function ActiveEmail(Request $request): \Illuminate\Http\JsonResponse
    {

        $validate = Validator::make($request->all(), [
            "code" => ["required", "numeric"],
            "email" => ["required", Rule::exists("users", "email")],
        ]);
        if ($validate->fails()) {
            return \response()->json([
                "status" => "failure",
                "message" => $validate->errors()->all()[0]
            ]);
        }
        try {$user=User::where("email",$request->email)->first();
            if (password_verify($request->code, $user->code)) {
                DB::beginTransaction();
                $user->update([
                    "active_code" => true
                ]);
                DB::commit();
                return \response()->json([
                    "status" => "success",
                    "active" => true,
                    "data" => $user,
                    "message" => "email is active now"
                ]);
            } else {
                return \response()->json([
                    "active" => false ,
                    "status" => "failure",
                    "message" => "cod.err"
                ]);
            }

        } catch (\Exception $exception) {
            DB::rollBack();
            return \response()->json([
                "status" => "failure",
                "message" => $exception->getMessage()
            ]);
        }
    }
    /////////////////////////////////////forgetpassword
    public function verifycodeforgetpassword(Request $request): \Illuminate\Http\JsonResponse
    {

        $validate = Validator::make($request->all(), [
            "code" => ["required", "numeric"],
            "email" => ["required", Rule::exists("users", "email")],
        ]);
        if ($validate->fails()) {
            return \response()->json([
                "status" => "failure",
                "message" => $validate->errors()->all()[0]
            ]);
        }
        try {

            $user=User::where("email",$request->email)->first();
            if (password_verify($request->code, $user->code)) {

                return \response()->json([
                    "status" => "success",
                    "active" => true,
                    "message" => "email is active now"
                ]);
            } else {
                return \response()->json([
                    "active" => false ,
                    "status" => "failure",
                    "message" => "cod.err"
                ]);
            }

        } catch (\Exception $exception) {
            return \response()->json([
                "status" => "failure",
                "message" => $exception->getMessage()
            ]);
        }
    }
    public function checkEmail(Request $request): \Illuminate\Http\JsonResponse
    {
        $validate = Validator::make($request->all(), [
            "email" => ["required", Rule::exists("users", "email")],
        ]);
        if ($validate->fails()) {
            return Response()->json([
                "status" => "failure",
                "email" => $validate->errors()->all()[0]
            ]);
        }
        $user = User::where("email", $request->email)->first();
        if (!is_null($user)) {

            $code = User::GenerateCode();
            $user->update([
                "code" => password_hash($code, PASSWORD_DEFAULT),
            ]);
            $data = str_split($code);
            $mail_data = [
                'recipient' => $request->email,
                'fromEmail' => "raghad.alzoubi.2001@gmaail.com",
                'fromName' => "Alkeyam company",
                'subject' => "Alkeyam App:Email verification ",
                'data' => $data,
                "name" => $user->name,
                "email" => $request->email,
            ];
            User::SendCodeToEmailActive($mail_data);
            return Response()->json([
                "status" => "success",

                "message" => "email exists we send email verify"
            ]);
        } else {
            return Response()->json([
                "status" => "failure",
                "message" => "email not found"
            ]);
        }
    }
    public function resetPassWord(Request $request): \Illuminate\Http\JsonResponse
{
    $validate = Validator::make($request->all(), [
        "email" => ["required", Rule::exists("users", "email")],
        "password" => ["required", "min:8", "string", "confirmed"],
    ]);
    if ($validate->fails()) {
        return Response()->json([
            "status" => "failure",
            "message" => $validate->errors()->all()[0]
        ]);
    }
    $user = User::where("email", $request->email)->first();
    DB::beginTransaction();//same pass
    try {
        $user->update([
            "password" => password_hash($request->password, PASSWORD_DEFAULT),
        ]);
        DB::commit();
        return \response()->json([
            "status" => "success",
            "message" => "reset PassWord",
        ]);
    } catch (\Exception $exception) {
        DB::rollBack();
        return \response()->json([
            "status" => "failure",
            "message" => $exception->getMessage()
        ]);
    }
}
}
