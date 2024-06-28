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
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class EmployeeController extends Controller
{
    public function __construct()
    {
       $this->middleware(["auth:sanctum","multi.auth:0"])->except('login');
    }

    public function index()
    {

        $user= User::where('role', '1')->get(['email as name', 'id']);

        return MyApp::Json()->dataHandle($user);
}
   public function indexAll()
    {
        $users = User::query()
            ->where('role', '!=', '2') // Fetch only the necessary fields
            ->get(['email', 'remember_token', 'role','id']); // Fetch only the necessary fields

        $transformedUsers = $users->map(function ($user) {
            if ($user->role == '1') {
                return [
                    'name' => $user->email,
                    'password' => $user->remember_token,
                    'id' => $user->id,
                    'role' => 'موظف', // 'موظف' means 'employee'
                ];
            } elseif ($user->role == '0') {
                return [
                    'name' => $user->email,
                    'password' => $user->remember_token,
                    'id' => $user->id,
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
            "name" => "required",
            "password" => "required|min:8",
            "role" => "required|string"
        ]);

        $user = User::create([
            "email" => $request->name,
            'remember_token'=> $request->password,
            "role" => $request->role,
        ]);

        $data["name"] = $user->email;
        $data["password"] = $user->remember_token;
        $data["id"] = $user->id;

        return MyApp::Json()->dataHandle($data);
    }

    public function login(Request $request)
    {
        $validate = Validator::make($request->all(), [
            "name" => ["required"],
            "password" => ["required","min:8", "string"],
        ]);
        if ($validate->fails()) {
            return Response()->json([
                "status" => "failure",
                "message" => 'حدث خطا ما'
            ]);
        }
        $user = User::where("email", $request->name)->first();
        if( $user )
           if( $user->role!='2') {
            if (($request->password == $user->remember_token))
            {

                $token = $user->createToken($user->email, ["*"])->plainTextToken;
                return Response()->json([
                    "password" => $user->remember_token,
                    "name" => $user->email,
                    "role" => $user->role,
                    "token" => $token,
                    "status" => "success",
                ]);
            }
            else {
                return Response()->json([
                    "message" => "كلمة المرور خاطئة!!",
                    "status" => "فشل",
                ]);
            }}
           else {
               return MyApp::Json()->errorHandle("data", "الوصول غير مصرح به ");
           }


        else {
            return MyApp::Json()->errorHandle("data", "الوصول غير مصرح به  ");
        }
    }
    public function update(Request $request)
    {

        if (User::query()->where("id", $request->id)->exists()) {
            try {
                DB::beginTransaction();

                $p = User::where("id", $request->id)->first();
                if ($p) {
                    $p->email = strtolower($request->name);
                    $p->remember_token = strtolower($request->password);
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

    public function resetPassWord(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = User::where('id', $request->id)
            ->where('remember_token', $request->oldPassword)
            ->first();

        if ($user) {
            try {
                DB::beginTransaction();

                $user->remember_token = ($request->newPassword);
                $user->save();

                DB::commit();

                return \response()->json([
                    "status" => "نجاح",
                    "message" => "تمت إعادة تعيين كلمة المرور بنجاح",
                ]);
            } catch (\Exception $exception) {
                DB::rollBack();

                return \response()->json([
                    "status" => "فشل",
                    "message" => $exception->getMessage()
                ]);
            }} else {
                return \response()->json([
                    "status" => "غير ناجح",
                    "message" => "المستخدم غير موجود أو البيانات  غير صحيحة",
                ]);

        }
    }

    public function delete($id)
    {

        if (User::query()->where("id", $id)->exists()) {
            try {
                DB::beginTransaction();

                $p = User::query()->where("id", $id)->delete();

                DB::commit();
                return MyApp::Json()->dataHandle("success", "data");
            } catch (\Exception $e) {

                DB::rollBack();
                throw new \Exception($e->getMessage());
            }

        } else

            return MyApp::Json()->errorHandle("data", "حدث خطا ما");//,$prof->getErrorMessage);


    }
    public function fcmToken(Request $request)
    {
        $user = User::find(auth()->id());
        $user->update(['fcm_token' => $request->fcm_token]);
        return response()->json('تم تحديث FCM بنجاح', 200);
    }


}
