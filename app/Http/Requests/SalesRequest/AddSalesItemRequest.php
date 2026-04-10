<?php

namespace App\Http\Requests\SalesRequest;

use Illuminate\Foundation\Http\FormRequest;

class AddSalesItemRequest extends FormRequest
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
            'sale_id' => 'required|exists:sales,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric|min:1',
            'discount' => 'nullable|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'sale_id.required' => 'Sale ID missing',
            'sale_id.exists' => 'Invalid sale',

            'product_id.required' => 'Select product',
            'product_id.exists' => 'Invalid product',

            'quantity.required' => 'Quantity required',
            'quantity.numeric' => 'Quantity must be number',
            'quantity.min' => 'Minimum quantity is 1',

            'discount.numeric' => 'Discount must be number',
            'discount.min' => 'Discount cannot be negative',
        ];
    }
}
