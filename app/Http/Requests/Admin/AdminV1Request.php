<?php

namespace App\Http\Requests\Admin;

use App\Enums\ValidationEnum;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AdminV1Request extends FormRequest
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
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date|date_format:Y-m-d',
            'location' => 'required|string|max:255',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param Validator $validator
     * @return void
     * @throws HttpResponseException
     */
    public function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json([
            'success'   => false,
            'message'   => 'Validation errors',
            'data'      => $validator->errors()
        ]));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function messages(): array
    {
        return [
            'name.required' => ValidationEnum::REQUIRED_MESSAGE->value,
            'name.string' => ValidationEnum::STRING_MESSAGE->value,
            'name.max' => 'The :attribute may not be greater than :max characters.',
            'description.required' => ValidationEnum::REQUIRED_MESSAGE->value,
            'description.string' => ValidationEnum::STRING_MESSAGE->value,
            'date.required' => ValidationEnum::REQUIRED_MESSAGE->value,
            'date.date' => 'The :attribute must be a valid date.',
            'date.date_format' => 'The :attribute must be a YYYY-MM-DD format.',
            'location.required' => ValidationEnum::REQUIRED_MESSAGE->value,
            'location.string' => ValidationEnum::STRING_MESSAGE->value,
            'location.max' => 'The :attribute may not be greater than :max characters.',
        ];
    }
}
