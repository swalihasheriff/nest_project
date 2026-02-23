<?php

namespace App\Http\Requests\ProductRequest;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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

            'description' => 'required|string|max:255',
            'supplier_id' => 'required|exists:suppliers,id',

            'ctn_barcode' => [
                'required',
                'string',                
                'unique:products,ctn_barcode,' . $this->route('product') 
            ],
            'upc' => 'required|string|max:100',
            'product_barcode1' => [
                'required',
                'string',
                'unique:products,product_barcode1,' . $this->route('product')
            ],

            'ctn_cost_price' => 'required|numeric|min:0',
            'ctn_sell_price' => 'required|numeric|min:0',

            'stock_on_hand' => 'required|integer|min:0',
            'minimum_threshold' => 'required|integer|min:0',

            'location' => 'required|string|max:100',
            'uom' => 'required|string|max:50',

            'product_barcode2' => 'nullable|string|max:100',
            'product_barcode3' => 'nullable|string|max:100',

            'gst' => 'nullable|numeric|min:0|max:100',

            'sell_price2' => 'nullable|numeric|min:0',
            'sell_price3' => 'nullable|numeric|min:0',
            'sell_price4' => 'nullable|numeric|min:0',
            'sell_price5' => 'nullable|numeric|min:0',

            'weight' => 'nullable|string|max:50',
            'length' => 'nullable|string|max:50',

            'product_code' => 'nullable|string|max:100',
            'supplier_code' => 'nullable|string|max:100',

            'shelf_capacity' => 'nullable|integer|min:0',

            'reorder' => 'nullable|boolean',

            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
        ];
    }

    public function messages(): array
    {
        return [

        'description.required' => 'Description is required.',
        'supplier_id.required' => 'Please select a supplier.',
        'supplier_id.exists' => 'Selected supplier is invalid.',

        'ctn_barcode.required' => 'CTN Barcode is required.',
        'ctn_barcode.unique' => 'CTN Barcode already exists.',

        'upc.required' => 'UPC is required.',

        'product_barcode1.required' => 'Product Barcode 1 is required.',
        'product_barcode1.unique' => 'Product Barcode 1 already exists.',

        'ctn_cost_price.required' => 'Cost price is required.',
        'ctn_cost_price.numeric' => 'Cost price must be a number.',
        'ctn_cost_price.min' => 'Cost price cannot be negative.',


        'ctn_sell_price.required' => 'Sell price is required.',
        'ctn_sell_price.numeric' => 'Sell price must be a number.',
        'ctn_sell_price.min' => 'Sell price cannot be negative.',

        'stock_on_hand.required' => 'Stock on hand is required.',
        'stock_on_hand.integer' => 'Stock on hand must be an integer.',
        'stock_on_hand.min' => 'Stock on Hand cannot be negative.',

        'minimum_threshold.required' => 'Minimum threshold is required.',
        'minimum_threshold.integer' => 'Minimum threshold must be an integer.',
        'minimum_threshold.min' => 'Minimum threshold cannot be negative.',
        

        'location.required' => 'Location is required.',
        'uom.required' => 'UOM is required.',

        // OPTIONAL fields
        'gst.numeric' => 'GST must be a valid number.',
        
        'gst.max' => 'GST cannot exceed 100%.',

        'sell_price2.numeric' => 'Sell Price 2 must be a number.','sell_price2.min' => 'Sell price 2 cannot be negative.',
        'sell_price3.numeric' => 'Sell Price 3 must be a number.','sell_price3.min' => 'Sell price 3 cannot be negative.',
        'sell_price4.numeric' => 'Sell Price 4 must be a number.','sell_price4.min' => 'Sell price 4 cannot be negative.',
        'sell_price5.numeric' => 'Sell Price 5 must be a number.','sell_price5.min' => 'Sell price 5 cannot be negative.',

        'shelf_capacity.integer' => 'Shelf capacity must be an integer.','shelf_capacity.min' => 'Shelf capacity cannot be negative.',

        'images.*.image' => 'Each file must be an image.',
        'images.*.mimes' => 'Images must be jpg, jpeg, png, or webp.',
        'images.*.max' => 'Each image must be less than 2MB.',
    ];
}
}