<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * Class RegisterRequest
 *
 * Request class for user registration validation.
 *
 * @package App\Http\Requests\Auth
 */
class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array {
        return [
            'name' => 'required|regex:/^[\pL\s\-]+$/u|min:4|max:25',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,20}$/',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param Validator $validator
     * @return void
     * @throws HttpResponseException
     */
    public function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json([
            'success'   => false,
            'message'   => 'Validation errors',
            'data'      => $validator->errors()
        ]));
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The :attribute field is required.',
            'name.regex' => 'The :attribute must contain only letters, spaces, and hyphens.',
            'name.min' => 'The :attribute must be at least :min characters long.',
            'name.max' => 'The :attribute may not be greater than :max characters.',
            'email.required' => 'The :attribute field is required.',
            'email.email' => 'The :attribute must be a valid email address.',
            'email.unique' => 'The :attribute has already been taken.',
            'password.required' => 'The :attribute field is required.',
            'password.regex' => 'The :attribute must be between 8 and 20 characters long and contain at least one uppercase letter, one lowercase letter, one numeric character, and one special character (@, $, !, %, *, ?, or &).',
        ];
    }
}
