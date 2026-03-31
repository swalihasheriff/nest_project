<?php

namespace App\Http\Requests\GoodsReceivingRequest\InvoiceRequest;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInvoiceItemRequest extends FormRequest
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
            'id' => 'required|exists:manual_invoice_items,id',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => 'Item not found',
            'price.required' => 'Price is required',
            'price.numeric' => 'Price must be a number',
            'quantity.required' => 'Quantity is required',
            'quantity.integer' => 'Quantity must be a number',
        ];
    }
}
