<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Support\Traits\Api\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request)
    {
        $cart = Cart::with('cartItems.product')->where('user_id', $request->user()->id)->first();

        if (!$cart) {
            return $this->notFoundResponse('Cart not found');
        }

        return $this->successResponse($cart);
    }

    public function addItem(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);
        Log::info('addItem => request data',[$request->all()]);
        Log::info('addItem => validated data',[$validated]);

        $cart = Cart::firstOrCreate(
            ['user_id' => $request->user()->id]
        );

        $item = $cart->cartItems()->updateOrCreate(
            ['product_id' => $validated['product_id']],
            ['quantity' => $validated['quantity']]
        );

        return $this->successResponse($item, 'Item added to cart', 201);
    }

    public function updateItem(Request $request, $itemId)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $item = CartItem::findOrFail($itemId);

        $item->update(['quantity' => $validated['quantity']]);

        return $this->successResponse($item, 'Item quantity updated');
    }

    public function removeItem($itemId)
    {
        $item = CartItem::findOrFail($itemId);

        $item->delete();

        return $this->successResponse(null, 'Item removed successfully');
    }

    public function clearCart(Request $request)
    {
        $cart = Cart::where('user_id', $request->user()->id)->first();

        if ($cart) {
            $cart->cartItems()->delete();
        }

        return $this->successResponse(null, 'Cart cleared successfully');
    }
}
