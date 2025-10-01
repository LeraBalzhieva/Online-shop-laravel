<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
            'name' => 'required|string|min:3|max:255',
            'phone' => 'required|string|min:10',
            'city' => 'required|string',
            'address' => 'required|string',
            'comment' => 'required|string'
        ];
    }
    public function messages(): array
    {
        return [

            'name.required' => 'Поле "Имя" обязательно для заполнения.',
            'name.string' => 'Введите корректное имя',
            'name.min' => 'Введите корректное имя',
            'phone.required' => 'Поле "Телефон" обязательно для заполнения.',
            'phone.string' => 'Введите корректный телефон',
            'phone.min' => 'Введите корректный телефон',
            'city.required' => 'Поле "Город" обязательно для заполнения.',
            'city.string' => 'Введите корректный город',
            'address.required' => 'Поле "Адрес" обязательно для заполнения.',
            'address.string' => 'Введите корректный адрес',
        ];
    }

}
