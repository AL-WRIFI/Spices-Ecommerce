<?php

namespace App\Http\Controllers\Api\Product;

use App\Actions\Product\FetchProductAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\FetchProductRequest;
use App\Models\Product;
use App\Models\SubCategory;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    
    public function fetch(FetchProductRequest $request, FetchProductAction $fetchProductAction)
    {    
        $products = $fetchProductAction->handle($request->validated()['filters'] ?? []);    
        return response()->json(['products' => $products],200);
    }
}