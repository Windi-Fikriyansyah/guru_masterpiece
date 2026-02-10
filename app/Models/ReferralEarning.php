<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReferralEarning extends Model
{
    protected $fillable = [
        'referrer_id',
        'referee_id',
        'transaction_id',
        'amount',
        'percent',
    ];

    public function referrer()
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }

    public function referee()
    {
        return $this->belongsTo(User::class, 'referee_id');
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
