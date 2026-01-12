<?php

namespace App\Services;

use Stripe\StripeClient;
use Stripe\PaymentIntent;
use App\Models\Invoice;

class StripeService
{
    protected $stripe;

    public function __construct()
    {
        // Initialize Stripe client with your secret key
        $this->stripe = new StripeClient(config('services.stripe.secret'));
    }

    public function chargeInvoice(Invoice $invoice)
    {
        // Stripe expects amount in cents
        $amountInCents = intval($invoice->amount * 100);

        // Create a payment intent
        $paymentIntent = $this->stripe->paymentIntents->create([
            'amount' => $amountInCents,
            'currency' => 'usd',
            'payment_method' => 'pm_card_visa', // test card
            'confirm' => true,
            'automatic_payment_methods' => [
                'enabled' => true,
                'allow_redirects' => 'never'   // Prevent redirect errors
            ],
            'metadata' => [
                'invoice_id' => $invoice->id,
                'user_id' => $invoice->user_id,
            ],
        ]);

        // Save the PaymentIntent ID in the invoice for webhook tracking
        $invoice->stripe_payment_intent_id = $paymentIntent->id;
        $invoice->save();

        return $paymentIntent;
    }

}
