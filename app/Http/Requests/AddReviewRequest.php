<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddReviewRequest extends FormRequest
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
            'comment' => 'required|string|max:1000',
            'rating' => 'required|integer|between:1,5',
            'product_id' => 'required|exists:products,id'
        ];
    }

    public function messages()
    {
        return [
            'comment.required' => 'Поле отзыва обязательно для заполнения',
            'rating.required' => 'Необходимо выбрать оценку',
            'rating.between' => 'Оценка должна быть от 1 до 5',
        ];
    }
}
