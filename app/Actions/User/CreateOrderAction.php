<?php

namespace App\Actions\User;

use App\Models\Order;
use App\Models\Cart;
use App\Models\User;
use App\Support\Services\PaymentService;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;

class CreateOrderAction
{
    protected PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function handle(array $data)
    {
        return DB::transaction(function () use ($data) {
            $user = auth()->user();
            $cart = $user->cart;
    
            if (!$cart || $cart->cartItems->isEmpty()) {
                throw new Exception('Cart is empty or does not exist.');
            }

            $orderData = $this->prepareOrderData($user, $cart, $data);
            Log::info('CreateOrderAction => handle orderData',[$orderData]);

            $order = Order::create($orderData);

            $this->createOrderItems($order, $cart);

            if ($data['payment_method'] !== 'cod') {
                // $this->paymentService->processPayment($order, $data);
            }

            $cart->cartItems()->delete();

            return $order;
        });
    }

    protected function prepareOrderData($user, Cart $cart, array $data): array
    {
        return [
            'subtotal' => $cart->subtotal,
            'discount_amount' => $cart->discount_amount,
            'coupon_id' => $cart->coupon_id,
            'coupon_code' => $cart?->coupon?->code ?? null,
            'delivery_amount' => $cart->delivery_amount ?? 0,
            'total_amount' => $cart->total_amount,
            'status' => 'pending',
            'user_id' => $user->id,
            // 'latitude' => $user->
            // 'longitude' => $user->
            'shipping_address' => $data['shipping_address'] ?? null,
            'payment_method' => $data['payment_method'],
        ];
    }

    protected function createOrderItems(Order $order, Cart $cart): void
    {
        foreach ($cart->cartItems as $item) {
            $order->orderItems()->create([
                'product_id' => $item->product_id,
                'product_name' => $item->product->name,
                'product_price' => $item->item_price,
                'quantity' => $item->quantity,
            ]);
        }
    }
}