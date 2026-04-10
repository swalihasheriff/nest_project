<?php

namespace App\Http\Requests\SalesRequest;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSalesRequest extends FormRequest
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
            'delivery_date' => 'nullable|date',
            'reference' => 'nullable|string|max:255',
            'delivery_address' => 'nullable|string',
            'round' => 'nullable|numeric|min:0',
            'payment_mode' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [

            'delivery_date.date' => 'Enter valid date',

            'reference.max' => 'Reference must not exceed 255 characters',
            'delivery_address.string' => 'Delivery address must be valid text',

            'round.numeric' => 'Round must be a number',
            'round.min' => 'Round cannot be negative',

            'payment_mode.required' => 'Payment mode is required',
            'payment_mode.string' => 'Invalid payment mode selected',
        ];
    }
}
