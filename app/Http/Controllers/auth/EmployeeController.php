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
        $this->middleware(["auth:sanctum","multi.auth:0"])->except(['Login','update']);
        $this->middleware(["auth:sanctum","multi.auth:0|1"])->only(['Login','update']);
    }

    public function index()
    {

        $user = User::query()->where('role', '1')->get(['email', 'id']);

        return MyApp::Json()->dataHandle($user);
    }
    public function indexAll()
    {


        $users = User::query()
        ->where ('role','!=','2') // Fetch only the necessary fields
     // ->where ('role','=','0')
            ->get(['email', 'password', 'role']); // Fetch only the necessary fields

        $transformedUsers = $users->map(function ($user) {
            if ($user->role == '1') {
                return [
                    'email' => $user->email,
                    'password' => $user->password,
                    'role' => 'موظف', // 'موظف' means 'employee'
                ];
            } elseif ($user->role == '0') {
                return [
                    'email' => $user->email,
                    'password' => $user->password,
                    'role' => 'مدير', // 'مدير' means 'manager'
                ];
            }

         return $user; // In case the role is neither 0 nor 1, return the user as is
        });

        return MyApp::Json()->dataHandle($transformedUsers);
    }
   public function create(Request $request)
    {
        $request->validate([
            "name" => ["required", Rule::unique("users", "email")],
            "password" => ["required", "min:8"],
            "role" => ["required","string"],//Rule::in([Role::Admin->value,Role::User->value])],
        ]);
        $user = User::create([
            "email" => $request->name,
            "password" => ($request->password),
            "role" => $request->role,
        ]);
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

}
