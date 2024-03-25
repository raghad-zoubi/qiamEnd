<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\MyApplication\MyApp;
use App\MyApplication\Role;
use App\MyApplication\Services\FileRuleValidation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * @property FileRuleValidation rules
 */
class UserController extends Controller
{ public function __construct()
{
    $this->middleware(["auth:sanctum"])->only("Logout");
   // $this->rules = new FileRuleValidation();
}

    public function Register(Request $request)
    {
        $request->validate([
            "email" => ["required",Rule::unique("users","email"),"email"],
            "password" => ["required","min:8"],
               "role" => ["nullable","string"],//Rule::in([Role::Admin->value,Role::User->value])],
        ]);
        $user = User::create([
            "email" => $request->email,
            "password" => password_hash($request->password,PASSWORD_DEFAULT),
            "role" =>  $request->role
        ]);
        $token = $user->createToken('ProductsTolken')->plainTextToken;
        $data["id"] = $user->id;
        $data["email"] = $user->email;
        $data["access_token"] =$token;


        return MyApp::Json()->dataHandle($data);
    }
    public function Login(Request $request)
    {
        $request->validate([
            "email" => ["required",Rule::exists("users","email")],
            "password" => ["required","min:8"],
        ]);
        $user = User::where("email",$request->email)->first();
        if (password_verify($request->password,$user->password)){
            return MyApp::Json()->dataHandle($user->createToken('ProductsTolken')->plainTextToken,"user");
        }
        $password = new class{};
        $password->password = ["the password is not valid"];
        return MyApp::Json()->errorHandle("Validation",$password);
    }
    public function Logout()
    {
        $user = auth()->user();
        $user->currentAccessToken()->delete();
        return MyApp::Json()->dataHandle("Successfully logged out","message");
    }

}
