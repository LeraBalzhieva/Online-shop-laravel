<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddProductRequest extends FormRequest
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
            'product_id' => 'integer|exists:products,id',
            'amount' => 'required|integer'
        ];
    }
    public function messages()
    {
        return [
            'product_id.integer' => 'Используйте цифры',
            'product_id.exists' => 'Продукт не найден',
            'amount.required' => 'Строка должна быть заполнена',
            'amount.integer' => 'Используйте цифры'
        ];
    }
}
