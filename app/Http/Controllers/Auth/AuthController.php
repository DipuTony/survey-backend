<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $req)
    {
        $validator = Validator::make($req->all(), [
            "name" => "required",
            "email" => "required|unique:users,email",
            "password" => "required",
            "isAdmin" => 'nullable|bool'
        ]);

        if ($validator->fails()) {
            return responseMsg(false, $validator->errors(), "");
        }
        try {
            $user = new User();
            $user->name = $req->name;
            $user->email = $req->email;
            $user->password = Hash::make($req->password);
            $user->is_admin = $req->isAdmin;
            $user->save();
            return responseMsg(true, "User Successfully Registered", "");
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function login(Request $req)
    {
        $req->validate([
            "email" => "required",
            "password" => "required"
        ]);
        try {
            if (Auth::attempt(['email' => $req->email, 'password' => $req->password])) {
                $user = Auth::user();
                $success['token'] = $user->createToken('MyApp')->plainTextToken;
                $success['name'] = $user->name;
                return responseMsg(true, $success['token'], "");
            }
            return responseMsg(false, "Email or Password Incorrect", "");
        } catch (Exception $e) {
            return responseMsg(false, $e->getMessage(), "");
        }
    }

    public function logout(Request $req)
    {
        auth()->user()->tokens()->delete();
        return responseMsg(true, "Deleted Successfully", "");
    }
}
