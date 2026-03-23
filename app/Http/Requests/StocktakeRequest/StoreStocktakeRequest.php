<?php

namespace App\Http\Requests\StocktakeRequest;

use Illuminate\Foundation\Http\FormRequest;

class StoreStocktakeRequest extends FormRequest
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
            'worksheet_name' => 'required|string|max:255',
            'type' => 'required|integer|in:1,2',
        ];
    }

    public function messages(): array
    {
        return [
            'worksheet_name.required' => 'Worksheet name is required',
            'type.required' => 'Please select stocktake type',
        ];
    }
}
