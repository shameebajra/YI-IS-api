<?php

namespace App\Http\Requests;

use App\Enums\Gender;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class EmployeeRequest extends FormRequest

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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['string'],
            'email' => ['email', 'unique:users,email'],
            'password' => ['min:6', 'max:18'],
            'gender' => ["in:" . implode(",", Gender::ALL)],
            'join_date' => ['date'],
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param \Illuminate\Support\Facades\Validator|Validator $validator
     * @return void
     */
    protected function failedValidation(
        \Illuminate\Support\Facades\Validator|Validator $validator
    ) {
        throw new HttpResponseException(
            response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422)
        );
    }
}
