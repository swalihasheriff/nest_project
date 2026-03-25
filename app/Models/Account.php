<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = [
        'company_name',
        'address',
        'abn',
        'email',
        'contact_name',
        'contact_number',
        'remarks',
        'accnt_ref_number',
        'username',
        'password',
        'price_group',
        'status',
    ];

    protected $hidden = [
        'password'
    ];

    protected function casts(): array{
        return [
            'status' => 'boolean',
            'password' => 'hashed',
        ] ;
    }
    
}
