<?php

namespace App\Http\Requests\AccountRequest;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'username' => 'nullable|string|max:100|unique:accounts,username,' . $this->route('id'),
            'password' => 'nullable|string|min:6',

            'price_group' => 'required|in:ctn,p1,p2,p3,p4,p5',

            'address' => 'nullable|string|max:255',
            'abn' => 'nullable|string|max:100',
            'contact_name' => 'nullable|string|max:100',
            'contact_number' => 'nullable|string|max:20',
            'remarks' => 'nullable|string',
            'accnt_ref_number' => 'nullable|string|max:100',

            'status' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'company_name.required' => 'Company name is required',

            'email.email' => 'Enter a valid email',

            'username.unique' => 'Username already exists',

            'password.min' => 'Password must be at least 6 characters',

            'price_group.required' => 'Please select a price group',
        ];
    }
}