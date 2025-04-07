<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignUpRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class UserController
{
    public function getSignUpForm()
    {
        return view('signUpForm');
    }
    public function getLogin()
    {
        return view('login');
    }
    public function signUp(SignUpRequest $request)
    {
        $data = $request->all();
        User::query()->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        return response()->redirectTo('login');
    }
    public function getProfile()
    {
        $user = Auth::user();
        return view('profile', ['user' => $user]);
    }
    public function getEditProfile()
    {
        $user = Auth::user();
        return view('edit_profile', ['user' => $user]);
    }

    public function login(LoginRequest $request)
    {
        $validatedData = $request->validated();

        if (Auth::attempt(['email' => $validatedData['email'], 'password' => $validatedData['password']])) {
            return redirect()->route('catalog');
        }

        return redirect()->route('login')->withErrors(['email' => 'Неверный логин или пароль.']);
    }
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
    public function editProfile(SignUpRequest $request)
    {
        $validatedData = $request->validated();

        User::query()->update([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);
        return redirect()->route('profile');

    }
}

