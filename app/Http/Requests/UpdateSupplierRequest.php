<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSupplierRequest extends FormRequest
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
        'name' => 'required|string|max:255',
        'phone' => 'required|digits:10',
        'email' => 'nullable|email|max:255',
        'address' => 'required|string',
        'contact_person' => 'required|string|max:255',
    ];
}

public function messages(): array
{
    return [
        'name.required' => 'Supplier name is required',
        'phone.required' => 'Phone number is required',
        'phone.digits' => 'Phone must be 10 digits',
        'address.required' => 'Address is required',
        'contact_person.required' => 'Contact person is required',
    ];
}

}
