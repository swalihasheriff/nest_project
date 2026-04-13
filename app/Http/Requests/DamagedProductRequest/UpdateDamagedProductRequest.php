<?php

namespace App\Http\Requests\DamagedProductRequest;

use App\Models\DamagedProduct;
use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDamagedProductRequest extends FormRequest
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
            'quantity' => 'required|integer|min:1'
        ];
    }

    public function messages(): array
    {
        return [
            'quantity.required' => 'Quantity is required',
            'quantity.integer' => 'Quantity must be a number',
            'quantity.min' => 'Minimum quantity is 1',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            $damaged = DamagedProduct::find($this->id);

            if (!$damaged)
                return;

            $product = Product::find($damaged->product_id);

            if (!$product)
                return;

            // restore old qty
            $availableStock = $product->stock_on_hand + $damaged->quantity;

            if ($this->quantity > $availableStock) {
                $validator->errors()->add(
                    'quantity',
                    'Not enough stock. Available: ' . $availableStock
                );
            }

        });
    }
}
