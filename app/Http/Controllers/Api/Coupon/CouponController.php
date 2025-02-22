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
}