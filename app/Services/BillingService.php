<?php

namespace App\Services;

use App\Models\User;
use App\Models\UsageRecord;
use App\Models\Invoice;

class BillingService
{
    public function generateInvoice(User $user, string $month)
    {
        $subscription = $user->subscription;
        $plan = $subscription->plan;

        $totalUsage = UsageRecord::where('user_id', $user->id)
            ->whereMonth('used_at', substr($month, 5, 2))
            ->whereYear('used_at', substr($month, 0, 4))
            ->sum('units');

        $billable = max(0, $totalUsage - $plan->free_units);
        $amount = $billable * $plan->price_per_unit;

        return Invoice::create([
            'user_id' => $user->id,
            'billing_month' => $month,
            'total_units' => $totalUsage,
            'billable_units' => $billable,
            'amount' => $amount,
            'status' => 'pending', // make sure to track status
        ]);
    }
}
