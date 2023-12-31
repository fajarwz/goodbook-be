<?php

namespace App\Http\Requests\Api\Member\Review;

use Illuminate\Foundation\Http\FormRequest;

class FormReviewRequest extends FormRequest
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
        $rules = [
            'Review' => 'required|integer|between:1,5',
        ];

        if (request()->isMethod('post')) {
            $rules[] = [
                'book_id' => 'required|exists:App\Models\Book,id',
            ];
        }

        return $rules;
    }
}
