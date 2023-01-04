<?php

namespace App\Repositories\Auth;

interface iAuthRepository
{
    public function register($req);
    public function login($req);
    public function logout($req);
}
