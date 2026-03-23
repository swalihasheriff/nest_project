<?php

namespace App\Http\Requests\StocktakeItemRequest;

use Illuminate\Foundation\Http\FormRequest;

class StoreStocktakeItemRequest extends FormRequest
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
            'barcode' => 'required|exists:products,product_barcode1',
            'count' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:255'
        ];
    }

    public function messages()
    {
        return [
            'barcode.required' => 'Barcode is required',
            'barcode.exists' => 'Invalid barcode',
            'count.required' => 'Count is required',
            'count.numeric' => 'Count must be a number'
        ];
    }
}
