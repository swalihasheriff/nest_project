<?php

namespace App\Http\Requests\DamagedProductRequest;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

class AddDamagedProductRequest extends FormRequest
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
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ];

    }

    public function messages(): array
    {
        return [
            'product_id.required' => 'Please select a product',
            'product_id.exists' => 'Invalid product selected',
            'quantity.required' => 'Quantity is required',
            'quantity.integer' => 'Quantity must be a number',
            'quantity.min' => 'Quantity must be at least 1',
        ];
    }
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (!$this->product_id) {
                return;
            }

            $product = Product::find($this->product_id);

            if ($product && $this->quantity > $product->stock_on_hand) {
                $validator->errors()->add(
                    'quantity',
                    'Not enough stock available. Current stock: ' . $product->stock_on_hand
                );
            }

        });
    }
}
