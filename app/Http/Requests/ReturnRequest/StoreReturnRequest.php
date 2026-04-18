<?php

namespace App\Http\Requests\ReturnRequest;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\WarehouseOrderItem;
use App\Models\ManualInvoiceItem;

class StoreReturnRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            // ✅ allow either one
            'goods_receiving_id' => ['nullable', 'exists:goods_receivings,id'],
            'manual_invoice_id'  => ['nullable', 'exists:manual_invoices,id'],

            // ❗ items
            'items' => ['required', 'array'],

            // ✅ updated field name
            'items.*.item_id' => ['required', 'integer'],

            'items.*.return_qty' => [
                'nullable',
                'integer',
                'min:0'
            ],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            $goodsReceivingId = $this->goods_receiving_id;
            $manualInvoiceId  = $this->manual_invoice_id;

            $hasValidReturn = false;

            foreach ($this->items as $index => $item) {

                $qty = (int) ($item['return_qty'] ?? 0);

                if ($qty <= 0) {
                    continue;
                }

                $hasValidReturn = true;

                $itemId = $item['item_id'];

                // 🔹 GOODS RECEIVING
                if ($goodsReceivingId) {

                    $orderItem = WarehouseOrderItem::find($itemId);

                    if (!$orderItem) {
                        $validator->errors()->add(
                            "items.$index.item_id",
                            "Invalid item selected"
                        );
                        continue;
                    }

                    if ($qty > $orderItem->quantity) {
                        $validator->errors()->add(
                            "items.$index.return_qty",
                            "Return qty cannot exceed ordered qty"
                        );
                    }
                }

                // 🔹 MANUAL INVOICE
                if ($manualInvoiceId) {

                    $invoiceItem = ManualInvoiceItem::find($itemId);

                    if (!$invoiceItem) {
                        $validator->errors()->add(
                            "items.$index.item_id",
                            "Invalid item selected"
                        );
                        continue;
                    }

                    if ($qty > $invoiceItem->quantity) {
                        $validator->errors()->add(
                            "items.$index.return_qty",
                            "Return qty cannot exceed ordered qty"
                        );
                    }
                }
            }

            // ❗ at least one qty > 0
            if (!$hasValidReturn) {
                $validator->errors()->add(
                    'items',
                    'Enter at least one return quantity greater than 0'
                );
            }

            // ❗ ensure one source exists
            if (!$goodsReceivingId && !$manualInvoiceId) {
                $validator->errors()->add(
                    'source',
                    'Invalid return source'
                );
            }
        });
    }

    public function messages(): array
    {
        return [
            'items.required' => 'At least one return item is required',
            'items.*.item_id.required' => 'Item is required',
            'items.*.return_qty.integer' => 'Return qty must be a number',
            'items.*.return_qty.min' => 'Return qty cannot be negative',
        ];
    }
}