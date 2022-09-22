<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function exists(Request $request): bool
    {
        $email = $request->get('email', 'unknown');
        return User::where('email', '=', $email)->exists();
    }

    
}
