<?php

namespace App\Http\Requests\Api\Loan;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLoanRequest extends FormRequest
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
            'id' => ['required', 'exists:loans,id'],
            'user_id' => ['exists:users,id'],
            'book_id' => ['exists:books,id'],
            'loan_date' => ['date'],
            'return_date' => ['date'],
        ];
    }
}
