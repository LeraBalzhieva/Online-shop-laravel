<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignUpRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController
{
    /**
     * Отображает форму регистрации
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|object
     */
    public function getSignUpForm()
    {
        return view('signUpForm');
    }

    /**
     * Отображает форму логина
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|object
     */
    public function getLogin()
    {
        return view('login');
    }

    /**
     * Регистрация нового пользователя
     * @param SignUpRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */

    public function signUp(SignUpRequest $request)
    {

        $data = $request->validated();
        $user = User::query()->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
        return redirect()->route('login');
    }

    /**
     * Отображает профиль текущего пользователя
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|object
     */

    public function getProfile()
    {
        $user = Auth::user();
        return view('profile', ['user' => $user]);
    }

    /**
     *  Форма редактирования профиля
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|object
     */

    public function getEditProfile()
    {
        $user = Auth::user();
        return view('edit_profile', ['user' => $user]);
    }

    /**
     * Логин
     * @param LoginRequest $request объект Request с данными для логина
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(LoginRequest $request)
    {
        $validatedData = $request->validated();

        if (Auth::attempt(['email' => $validatedData['email'], 'password' => $validatedData['password']])) {
            return response()->redirectTo('catalog');
        }
        return redirect()->route('login')->withErrors(['email' => 'Неверный логин или пароль.']);
    }

    /**
     * Логаут пользователя
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    /**
     * Редактирвание профиля
     * @param SignUpRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */

    public function editProfile(SignUpRequest $request)
    {
        $validatedData = $request->validated();

        $user = auth()->user();

        $user->query()->update([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);

        return redirect()->route('profile')->with('success', 'Профиль успешно обновлен!');
    }
}

