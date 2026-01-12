<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
class UsageSummaryController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();
        $invoice = $user->invoices()->latest()->first();

        if (!$invoice) {
            return response()->json([
                'message' => 'No invoice found for this user',
                'billing_month' => null,
            ], 200);
        }else{
            return response()->json([
                'plan' => $user->subscription->plan->name,
                'billing_month' => $invoice->billing_month,
                'total_usage' => $invoice->total_units,
                'free_usage' => $user->subscription->plan->free_units,
                'billable_usage' => $invoice->billable_units,
                'amount' => $invoice->amount,
                'status' => $invoice->status
            ]);
        }        
    }
}
