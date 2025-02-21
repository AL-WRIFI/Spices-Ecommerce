<?php 

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use Illuminate\Http\Request;
use App\Support\Traits\Api\ApiResponseTrait;

class FavoriteController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request)
    {
        $favorites = Favorite::with('product')->where('user_id', $request->user()->id)->get();

        return $this->successResponse($favorites);
    }

    public function addFavorite(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $favorite = Favorite::firstOrCreate(
            [
                'user_id' => $request->user()->id,
                'product_id' => $validated['product_id']
            ]
        );

        return $this->successResponse($favorite, 'Product added to favorites', 201);
    }

    public function removeFavorite($favoriteId)
    {
        $favorite = Favorite::findOrFail($favoriteId);

        if ($favorite->user_id !== request()->user()->id) {
            return $this->unauthorizedResponse();
        }

        $favorite->delete();

        return $this->successResponse(null, 'Product removed from favorites');
    }
}