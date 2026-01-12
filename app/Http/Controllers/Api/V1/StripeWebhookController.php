<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        try {
            $event = Webhook::constructEvent(
                $request->getContent(),
                $request->header('Stripe-Signature'),
                config('services.stripe.webhook_secret')
            );
        } catch (SignatureVerificationException $e) {
            // Invalid signature
            return response()->json(['error' => 'Invalid signature'], 400);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        // Handle events
        switch ($event->type) {
            case 'payment_intent.succeeded':
                Invoice::where('stripe_payment_intent_id', $event->data->object->id)
                    ->update(['status' => 'paid']);
                break;

            case 'payment_intent.payment_failed':
                Invoice::where('stripe_payment_intent_id', $event->data->object->id)
                    ->update(['status' => 'failed']);
                break;
        }

        return response()->json(['received' => true]);
    }
}
