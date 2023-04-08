<?php

namespace App\Services\Auth;

use App\Models\User;

class AuthService
{  
    public function register(array $userData): User
    {
        return User::create($userData);  
    } 
}
