<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\User;
use App\Services\StripeService;
use App\Services\BillingService;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;

class InvoiceController extends Controller
{
    protected $stripeService;
    protected $billingService;

    public function __construct(StripeService $stripeService,BillingService $billingService)
    {
        $this->stripeService = $stripeService;
        $this->billingService = $billingService;
    }

    /**
     * List all invoices for the authenticated user
     */
    public function index(Request $request)
    {
        $invoices = $request->user()->invoices()
            ->orderBy('billing_month', 'desc')
            ->get();

        return response()->json([
            'data' => $invoices
        ]);
    }

    /**
     * Show a single invoice
     */
    public function show(Request $request, Invoice $invoice)
    {
        // Ensure the invoice belongs to the user
        if ($invoice->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json([
            'data' => $invoice
        ]);
    }

    /**
     * Pay an invoice via Stripe
     */
    public function pay(Request $request, Invoice $invoice)
    {
        // Ensure the invoice belongs to the user
        if ($invoice->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Only pending invoices can be paid
        if ($invoice->status !== 'pending') {
            return response()->json(['message' => 'Invoice already paid or failed'], 400);
        }

        try {
            $paymentIntent = $this->stripeService->chargeInvoice($invoice);

            return response()->json([
                'message' => 'Invoice payment initiated',
                'payment_intent_id' => $paymentIntent->id,
                'client_secret' => $paymentIntent->client_secret
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Payment failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Temporary endpoint to generate invoices
    public function generate(Request $request)
    {
        $request->validate([
            'month' => 'required|date_format:Y-m'
        ]);

        $users = User::all();
        $invoices = [];

        foreach ($users as $user) {
            $invoice = $this->billingService->generateInvoice($user, $request->month);
            $invoices[] = $invoice;
        }

        return response()->json([
            'message' => 'Invoices generated successfully',
            'invoices' => $invoices
        ]);
    }
}
