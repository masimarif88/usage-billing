<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'user_id',
        'billing_month',
        'total_units',
        'billable_units',
        'amount',
        'status',
        'stripe_payment_intent_id'
    ];
}
