<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'reference',
        'merchant_ref',
        'amount',
        'temporary_password',
        'package',
        'payment_method',
        'payment_name',
        'status',
        'payment_url',
        'checkout_url',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
