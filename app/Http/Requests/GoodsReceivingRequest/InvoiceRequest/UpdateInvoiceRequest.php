<?php

namespace App\Http\Requests\GoodsReceivingRequest\InvoiceRequest;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInvoiceRequest extends FormRequest
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
        $id = $this->route('id');

        return [
           

            'invoice_number' => 'required|string|max:100|unique:manual_invoices,invoice_number,' . $id,

            'invoice_date' => 'required|date|before_or_equal:today',

            'received_on' => 'required|date|after_or_equal:invoice_date|before_or_equal:today',

            'received_by' => 'required|string|max:100',

            'amount' => 'required|numeric|min:0',

            'rounding' => 'nullable|numeric',

            'status' => 'nullable|in:1,2',
        ];
    }

    public function messages(): array
    {
        return [
            'invoice_number.required' => 'Invoice number is required',
            'invoice_number.unique' => 'Invoice already exists',

            'invoice_date.before_or_equal' => 'Invoice date cannot be in the future',

            'received_on.after_or_equal' => 'Received date must be after invoice date',
            'received_on.before_or_equal' => 'Received date cannot be in the future',

            'received_by.required' => 'Received by is required',

            'amount.required' => 'Amount is required',
            'amount.numeric' => 'Amount must be a number',
        ];
    }
}


