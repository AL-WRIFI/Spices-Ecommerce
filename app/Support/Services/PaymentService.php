<?php

namespace App\Support\Services;

use App\Models\Order;
use Exception;
use Stripe\StripeClient;

class PaymentService
{
    protected StripeClient $stripe;

    public function __construct()
    {
        $this->stripe = new StripeClient(config('services.stripe.secret'));
    }

    public function processPayment(Order $order, array $data)
    {
        try {
            $paymentIntent = $this->stripe->paymentIntents->create([
                'amount' => $order->total_amount * 100, // Convert to cents
                'currency' => 'usd',
                'payment_method' => $data['payment_method'],
                'confirmation_method' => 'manual',
                'confirm' => true,
            ]);

            $order->update([
                'payment_status' => 'paid',
                'payment_id' => $paymentIntent->id,
            ]);
        } catch (Exception $e) {
            throw new Exception('Payment processing failed: ' . $e->getMessage());
        }
    }
}