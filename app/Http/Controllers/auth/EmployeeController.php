<?php

namespace App\Http\Controllers\auth;
use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\User;
use App\MyApplication\MyApp;
use App\MyApplication\Role;
use App\MyApplication\Services\FileRuleValidation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;


class EmployeeController extends Controller
{
    public function __construct()
    {
        //  $this->middleware(["auth:sanctum"])->only("Logout");
    }

    public function index()
    {

        $user = User::query()->where('role', '1')->get(['email', 'id']);

        return MyApp::Json()->dataHandle($user);
    }

    public function inddexAll()
    {

        $users = User::query()->with('profile')->get(['email', 'password', 'profile.name', 'role']);

// Iterate through each user and modify the role if it's equal to 1
        $users->transform(function ($user) {
            if ($user->role == 1) {
                $user->role = 'user';
            }
            return $user;
        });

// Return the transformed data
        return MyApp::Json()->dataHandle($users);

    }


    public function indexAll()
    {
        // Hash the password for storage
//        $hashedPassword = password_hash($request->password, PASSWORD_DEFAULT);
//
//// Encode the password for transmission
//        $encodedPassword = ($request->password);

        $users = User::query()->with('profile')->get();//['email', 'password', 'profile.name', 'role']);
        $users->transform(function ($user) {
            if ($user->role == 1) {
                return [
                    'name' => $user->email,
                    'password' =>  ($user->password),
                    'role' => 'موظف',
                ];
            } elseif ($user->role == 2) {
                return [
                    'password' =>  ($user->password),
                    'name' => optional($user->profile)->name,
                    'lastName' => optional($user->profile)->lastName,
                    'role' => 'مستخدم',
                ];
            }

            return $user;
        });

  return MyApp::Json()->dataHandle($users);
    }




public function create(Request $request)
    {
        $request->validate([
            "name" => ["required", Rule::unique("users", "email")],
            "password" => ["required", "min:8"],
            // "role" => ["nullable","string"],//Rule::in([Role::Admin->value,Role::User->value])],
        ]);
        $user = User::create([
            "email" => $request->name,
            "password" => ($request->password),
            "role" => '1',
            //$request->role
        ]);
        //  $token = $user->createToken('ProductsTolken')->plainTextToken;
        $data["password"] = $request->password;
        $data["name"] = $user->email;
        $data["id"] = $user->id;
        // $data["access_token"] =$token;


        return MyApp::Json()->dataHandle($data);
    }

    public function Login(Request $request)
    {
        $request->validate([
            "name" => ["required", Rule::exists("users", "email")],
            "password" => ["required"],
        ]);
        $user = User::where("email", $request->name)->first();
        if (password_verify($request->password, $user->password)) {
            return MyApp::Json()->dataHandle($user->createToken('ProductsTolken')->plainTextToken, "user");
        }
        $password = new class {
        };
        $password->password = ["the password is not valid"];
        return MyApp::Json()->errorHandle("Validation", $password);
    }

    public function update(Request $request, $id)
    {

        if (User::query()->where("id", $id)->exists()) {
            try {
                DB::beginTransaction();

                $p = User::where("id", $id)->first();
                if ($p) {
                    $p->email = strtolower($request->name);
                    $p->password = strtolower($request->password);
                    $p->role = '1';
                    $p->save();
                }
                DB::commit();
                return MyApp::Json()->dataHandle("edit successfully", "data");
            } catch (\Exception $e) {

                DB::rollBack();
                throw new \Exception($e->getMessage());
            }

        } else

            return MyApp::Json()->errorHandle("data", "حدث خطا ما");//,$prof->getErrorMessage);


        //return MyApp::Json()->dataHandle($data);
    }

    public function delete($id)
    {

        if (User::query()->where("id", $id)->exists()) {
            try {
                DB::beginTransaction();

                $p = User::where("id", $id)->first();

                DB::commit();
                return MyApp::Json()->dataHandle("success", "data");
            } catch (\Exception $e) {

                DB::rollBack();
                throw new \Exception($e->getMessage());
            }

        } else

            return MyApp::Json()->errorHandle("data", "حدث خطا ما");//,$prof->getErrorMessage);


    }
    ///
}
