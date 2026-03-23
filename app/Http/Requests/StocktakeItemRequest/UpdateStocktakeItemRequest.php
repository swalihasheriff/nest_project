<?php

namespace App\Http\Requests\StocktakeItemRequest;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStocktakeItemRequest extends FormRequest
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
            'count' => 'required|integer|min:0',
            'notes' => 'nullable|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'count.required' => 'Please enter the count',
            'count.integer' => 'Count must be a number',
            'count.min' => 'Count cannot be negative',
            'notes.string' => 'Notes must be text',
            'notes.max' => 'Notes cannot exceed 255 characters',
        ];
    }
}


