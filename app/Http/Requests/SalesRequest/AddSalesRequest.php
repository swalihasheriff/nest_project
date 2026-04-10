<?php

namespace App\Http\Requests\SalesRequest;

use Illuminate\Foundation\Http\FormRequest;

class AddSalesRequest extends FormRequest
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
            'account_id' => 'required|exists:accounts,id',
            'type' => 'required|boolean',
            'delivery_date' => 'nullable|date',
            'reference' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'account_id.required' => 'Account is required',
            'account_id.exists' => 'Selected account is invalid',

            'type.required' => 'Type is required',
            'type.boolean' => 'Invalid type selected',

            'delivery_date.date' => 'Enter a valid date',

            'reference.max' => 'Reference must not exceed 255 characters',
        ];
    }
}
