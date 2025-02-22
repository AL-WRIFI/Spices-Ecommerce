<?php 

namespace App\Http\Controllers\Api\Coupon;

use App\Http\Controllers\Controller;
use App\Http\Resources\Cart\CartResource;
use App\Models\Coupon;
use App\Support\Traits\Api\ApiResponseTrait;
use Illuminate\Http\Request;



class CouponController extends Controller
{
    use ApiResponseTrait;


    public function apply(Request $request)
    {
        $request->validate([
            'coupon' => 'required|string|exists:coupons,code',
        ]);

        $user = $request->user();
        $cart = $user->cart;
    
        if (!$cart || $cart->cartItems->isEmpty()) {
            return $this->errorResponse(msg:'Cart is empty or does not exist.', code:400);
        }

        $coupon = Coupon::where('code', $request['coupon'])->first();

        if (!$coupon->isValid()) return $this->errorResponse(msg:'Coupon is not valid');

        if($cart->subtotal < $coupon->min_amount) return $this->errorResponse(msg:"min order price is $coupon->min_amount");

        $cart->update([
            'coupon_id' => $coupon->id,
        ]);

        return $this->successResponse(data:[new CartResource($user->cart)], msg:'success');
    }



    public function remove(Request $request)
    {
        $user = $request->user();
        $cart = $user->cart;
    
        if (!$cart || $cart->cartItems->isEmpty()) {
            return $this->errorResponse(msg:'Cart is empty or does not exist.', code:400);
        }

        $cart->update(['coupon_id' => null]);

        return $this->successResponse(data:[new CartResource($user->cart)], msg:'success');

    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:255',
            'type' => 'required|string',
            'amount' => 'required|numeric',
            'min_amount' => 'required|numeric',
            'max_discount_amount' => 'required|numeric',
            'status' => 'required|string',
            'usage_limit' => 'nullable|numeric',
            'used_count' => 'nullable|numeric',
            'expiry_date' => 'nullable|date',
        ]);

        $coupon = Coupon::create($validated);

        return response()->json([
            'message' => 'Coupon created successfully',
            'coupon' => $coupon,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:255',
            'type' => 'required|string',
            'amount' => 'required|numeric',
            'min_amount' => 'required|numeric',
            'max_discount_amount' => 'required|numeric',
            'status' => 'required|string',
            'usage_limit' => 'nullable|numeric',
            'used_count' => 'nullable|numeric',
            'expiry_date' => 'nullable|date',
        ]);

        $coupon = Coupon::findOrFail($id);

        $coupon->update($validated);

        return response()->json([
            'message' => 'Coupon updated successfully',
            'coupon' => $coupon,
        ], 200);
    }
}