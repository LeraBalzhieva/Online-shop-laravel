<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SignUpRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|min:2|max:255|regex:/^[a-zA-Zа-яА-ЯёЁ]+$/u',
            'email' => 'required|email' . auth()->id(),
            'password' => 'required|min:4|confirmed',
        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => "Необходимо заполнить поле Имя",
            'name.min' => 'Имя должно быть больше 2х символов',
            'email.required' => "Необходимо заполнить поле Email",
            'email.email' => 'Заполните корректно Email',
            'email.unique' => 'Такой пользователь уже есть',
            'email.min' => 'Email должен быть больше 5 символов',
            'password.min' => 'Пароль должен быть больше 4 символов',
            'password.confirmed' => 'Пароли не совпадают'

        ];
    }
}
