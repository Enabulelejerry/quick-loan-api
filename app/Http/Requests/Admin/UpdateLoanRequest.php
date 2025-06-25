<?php

namespace App\Http\Requests\Admin;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

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

            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('loan_products', 'name')->ignore($this->route('id')),
            ],
            'max_amount' => 'required|numeric|min:1000',
            'interest_rate' => 'required|numeric|min:0',
            'description' => 'required|string|min:5',
            'duration_days' => 'required|integer|min:1',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $this->validator = $validator;
        throw new HttpResponseException(response()->json([
            'error_code' => 'VALIDATION_ERROR',
            'message'    => 'The given data was invalid.',
            'errors'     => $validator->errors()
        ], 422));
    }
}
