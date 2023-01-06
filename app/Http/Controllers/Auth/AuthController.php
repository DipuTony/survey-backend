<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $req)
    {
        $validator = Validator::make($req->all(), [
            "name" => "required",
            "email" => "required|unique:users,email",
            "mobile" => "required|unique:users,mobile|numeric|digits:10",
            "password" => "required",
            "villageId" => "required|integer"
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
            $user->save();
            return responseMsg(true, "User Successfully Registered", "");
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function login(Request $req)
    {
        $req->validate([
            "mobile" => "required",
            "password" => "required"
        ]);
        try {
            if (Auth::attempt(['mobile' => $req->mobile, 'password' => $req->password])) {
                $user = Auth::user();
                $success['token'] = $user->createToken('MyApp')->plainTextToken;
                $success['name'] = $user->name;
                return responseMsg(true, $success['token'], "");
            }
            return responseMsg(false, "Mobile or Password Incorrect", "");
        } catch (Exception $e) {
            return responseMsg(false, $e->getMessage(), "");
        }
    }

    public function logout(Request $req)
    {
        auth()->user()->tokens()->delete();
        return responseMsg(true, "You have Logged Out!!", "");
    }
}
