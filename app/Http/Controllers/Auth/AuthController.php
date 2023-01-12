<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function register(Request $req)
    {
        $validator = Validator::make($req->all(), [
            "name" => "required",
            "email" => "required|unique:users,email",
            "mobile" => "required|unique:users,mobile|numeric|digits:10",
            "gramPanchayatId" => "required|integer",
            "password" => "required"
        ]);

        if ($validator->fails()) {
            return responseMsg(false, $validator->errors(), "");
        }
        try {
            $user = new User();
            $user->name = $req->name;
            $user->email = $req->email;
            $user->mobile = $req->mobile;
            $user->password = Hash::make($req->password);
            $user->gram_panchayat_id = $req->gramPanchayatId;
            $user->save();
            return responseMsg(true, "User Successfully Registered", "");
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * | Edit Employees
     */
    public function edit(Request $req)
    {
        $validator = Validator::make($req->all(), [
            "id" => "required|integer",
            "name" => "required",
            "email" => [
                "required", "email",
                Rule::unique('users')
                    ->ignore($req->id)
            ],
            "mobile" => [
                "required", "numeric", "digits:10",
                Rule::unique('users')
                    ->ignore($req->id)
            ],
            "gramPanchayatId" => "nullable|integer",
            "status" => "required|boolean"
        ]);

        if ($validator->fails()) {
            return responseMsg(false, $validator->errors(), "");
        }
        try {
            $user = User::find($req->id);
            $user->name = $req->name;
            $user->email = $req->email;
            $user->mobile = $req->mobile;
            $user->status = $req->status;
            if ($req->gramPanchayatId) {
                $user->gram_panchayat_id = $req->gramPanchayatId;
            }
            if ($req->password) {
                $user->password = Hash::make($req->password);
            }
            $user->save();
            return responseMsg(true, "User Successfully Updated", "");
        } catch (Exception $e) {
            return responseMsg(false, $e->getMessage(), "");
        }
    }

    // V2
    public function login(Request $req)
    {
        $req->validate([
            "mobile" => "required",
            "password" => "required"
        ]);
        try {
            if (Auth::attempt(['mobile' => $req->mobile, 'password' => $req->password])) {
                $user = Auth::user();
                if ($user->status == 0) {
                    return responseMsg(
                        true,
                        "You Are not Allowed to Logged in",
                        ""
                    );
                }
                $success['token'] = $user->createToken('MyApp')->plainTextToken;
                $success['name'] = $user->name;
                return response()->json(
                    [
                        'status' => true,
                        'bearer' => $success['token'],
                        'isAdmin' => $user->is_admin
                    ]
                );
            }
            return responseMsg(
                false,
                "Mobile or Password Incorrect",
                ""
            );
        } catch (Exception $e) {
            return responseMsg(false, $e->getMessage(), "");
        }
    }

    public function logout(Request $req)
    {
        auth()->user()->tokens()->delete();
        return responseMsg(true, "You have Logged Out!!", "");
    }

    /**
     * | Get all employee lists
     */
    public function getAllEmployees()
    {
        try {
            $user = new User();
            $employeeList = $user->getAllEmployees();
            return responseMsg(true, "Employee Lists", remove_null($employeeList->toArray()));
        } catch (Exception $e) {
            return responseMsg(false, $e->getMessage(), "");
        }
    }

    /**
     * | Employee Dtls
     */
    public function employeeDtls(Request $req)
    {
        $validator = Validator::make($req->all(), [
            "id" => "required|integer"
        ]);

        if ($validator->fails()) {
            return responseMsg(false, $validator->errors(), "");
        }
        try {
            $user = new User();
            $dtls = $user->employeeDtls($req->id);
            return responseMsg(true, "Employee Lists", remove_null($dtls));
        } catch (Exception $e) {
            return responseMsg(false, $e->getMessage(), "");
        }
    }

    /**
     * | Change password
     */
    public function changePassword(Request $req)
    {
        $validator = Validator::make($req->all(), [
            "mobile" => "required|numeric|digits:10",
            "oldPassword" => "required",
            "newPassword" => "required",
        ]);

        if ($validator->fails()) {
            return responseMsg(false, $validator->errors(), "");
        }

        // Logics
        try {
            $user = User::where('mobile', $req->mobile)
                ->first();
            if (Hash::check($req->oldPassword, $user->password)) {
                $user->password = Hash::make($req->newPassword);
                $user->save();
                return responseMsg(true, "Password Changed Successfully", "");
            }
            return responseMsg(
                false,
                "Mobile or Old Password Incorrect",
                ""
            );
        } catch (Exception $e) {
            return responseMsg(false, $e->getMessage(), "");
        }
    }
}
