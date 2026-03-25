<?php

namespace App\Http\Requests\AccountRequest;

use Illuminate\Foundation\Http\FormRequest;

class StoreAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_name'   => 'required|string|max:255',
            'email'          => 'required|email|max:255',
            'username'       => 'required|string|max:100|unique:accounts,username',
            'password'       => 'required|string|min:6',
            'price_group'    => 'required|in:ctn,p1,p2,p3,p4,p5',

            'address'        => 'nullable|string|max:255',
            'abn'            => 'nullable|string|max:100',
            'contact_name'   => 'nullable|string|max:100',
            'contact_number' => 'nullable|string|max:20',
            'remarks'        => 'nullable|string',
            'accnt_ref_number' => 'nullable|string|max:100',

            'status'         => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'company_name.required' => 'Company name is required',
            'email.required'        => 'Email is required',
            'email.email'           => 'Enter a valid email',
            'username.required'     => 'Username is required',
            'username.unique'       => 'Username already exists',
            'password.required'     => 'Password is required',
            'password.min'          => 'Password must be at least 6 characters',
            'price_group.required'  => 'Please select a price group',
        ];
    }
}