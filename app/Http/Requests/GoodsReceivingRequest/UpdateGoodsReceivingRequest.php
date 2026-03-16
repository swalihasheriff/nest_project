<?php

namespace App\Http\Requests\GoodsReceivingRequest;

use App\Models\WarehouseOrder;
use Illuminate\Foundation\Http\FormRequest;

class UpdateGoodsReceivingRequest extends FormRequest
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
        $order = WarehouseOrder::find($this->order_id);

        $orderDate = $order ? $order->created_at->format('Y-m-d') : null;

        return [
            'received_by' => ['required', 'string', 'max:100'],
            'received_on' => [
                'required',
                'date',
                'after_or_equal:' . $orderDate,
                'before_or_equal:today'
            ]
        ];
    }
    public function messages(): array
    {
        return [
            'received_by.required' => 'Receiver name is required',
            'received_on.required' => 'Received date is required',
            'received_on.date' => 'Please enter a valid date.',
            'received_on.after_or_equal' => 'Receiving date must be on or after the order date.',
            'received_on.before_or_equal' => 'Please enter a valid date.'
        ];
    }
}
