<?php

namespace App\Http\Requests\GoodsReceivingRequest\InvoiceRequest;

use Illuminate\Foundation\Http\FormRequest;

class StoreInvoiceItemRequest extends FormRequest
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
            'manual_invoice_id' => 'required|exists:manual_invoices,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'manual_invoice_id.required' => 'Invoice is required',
            'product_id.required' => 'Product is required',
            'quantity.required' => 'Quantity is required',
        ];
    }
}
