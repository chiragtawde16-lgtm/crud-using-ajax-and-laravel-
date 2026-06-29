<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // 😄 Register Page Open
public function register()
{
    return view('auth.register');
}

// 😄 Register Save
public function registerStore(Request $request)
{
    $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6'
    ]);

    User::create([
        'name' => $request->name,
        'email' => $request->email,

        // 😄 Password Hash
        'password' => Hash::make($request->password)
    ]);

    return response()->json([
        'message' => 'Registration Successful'
    ]);
}
// 😄 Login Page Open
public function login()
{
    return view('auth.login');
}

// 😄 Login Check
public function loginStore(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    if(Auth::attempt([
        'email' => $request->email,
        'password' => $request->password
    ]))
    {
        return response()->json([
            'message' => 'Login Successful'
        ]);
    }

    return response()->json([
        'message' => 'Invalid Email or Password'
    ], 401);
}
public function logout(Request $request)
{
    Auth::logout();

    $request->session()->invalidate();

    $request->session()->regenerateToken();

    return redirect('/login');
}
}
