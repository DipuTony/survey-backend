<?php

namespace App\Repositories\Auth;

use App\Models\User;
use App\Repositories\Auth\iAuthRepository;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;

class AuthRepository implements iAuthRepository
{
    public function register($req)
    {
        try {
            $user = new User();
            $user->name = $req->name;
            $user->email = $req->email;
            $user->password = Hash::make($req->password);
            $user->user_type = $req->userType;
            $user->center_id = $req->centerId;
            $user->center_code = $req->centerCode;
            $user->save();
            return responseMsg(true, "User Successfully Registered", "");
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function login($req)
    {
        try {
            if (Auth::attempt(['email' => $req->email, 'password' => $req->password])) {
                $user = Auth::user();
                $success['token'] = $user->createToken('MyApp')->plainTextToken;
                $success['name'] = $user->name;
                $userType = Config::get('constants.' . $user->user_type);
                return responseMsg(true, $success['token'], $userType);
            }
            return responseMsg(false, "Email or Password Incorrect", "");
        } catch (Exception $e) {
            return responseMsg(false, $e->getMessage(), "");
        }
    }

    public function logout($req)
    {
        auth()->user()->tokens()->delete();
        return responseMsg(true, "Deleted Successfully", "");
    }
}
