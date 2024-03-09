<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * Class StoreEventRequest
 *
 * Request class for storing a new event.
 *
 * @package App\Http\Requests\Admin
 */
class StoreEventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
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
    public function failedValidation(Validator $validator)
    {
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
    public function messages()
    {
        return [
            'name.required' => 'The :attribute field is required.',
            'name.string' => 'The :attribute must be a string.',
            'name.max' => 'The :attribute may not be greater than :max characters.',
            'description.required' => 'The :attribute field is required.',
            'description.string' => 'The :attribute must be a string.',
            'date.required' => 'The :attribute field is required.',
            'date.date' => 'The :attribute must be a valid date.',
            'date.date_format' => 'The :attribute must be a YYYY-MM-DD format.',
            'location.required' => 'The :attribute field is required.',
            'location.string' => 'The :attribute must be a string.',
            'location.max' => 'The :attribute may not be greater than :max characters.',
        ];
    }
}
