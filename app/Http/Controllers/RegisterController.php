<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;


class RegisterController extends Controller
{
    
    public function showRegistrationForm()
    {
        return view('auth.register');
    }
 
    
    public function register(Request $request)
    {
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'student_number' => 'required|string|max:20|unique:users',
            'phone' => User::rules(),
            'password' => 'required|string|min:8|confirmed',
        ]);

        
        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'student_number' => $data['student_number'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
            'role' => 'user'
        ]);

        
        return redirect()->route('home')->with('success', 'ثبت‌نام شما با موفقیت انجام شد.');
    }

    
}