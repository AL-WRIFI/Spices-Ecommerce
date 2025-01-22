<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\SubCategory;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    
    public function index(Request $request)
    {    
        if($request->wantsJson()) {
            $products = Product::with(['subCategory', 'unit'])->simplePaginate(10);
            return response()->json([
                'products' => $products,
            ],200);
        }

        $products = Product::with(['subCategory', 'unit'])->get();
        $categories = SubCategory::pluck('name', 'id')->toArray();    

        return view('admin.products.index', compact('products', 'categories'));
    }
    
    public function create()
    {
        $subCategories = SubCategory::all();
        $units = Unit::pluck('name', 'id')->toArray();
        return view('admin.products.create', compact('subCategories', 'units'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'sale_price' => 'nullable|numeric',
            'sub_category_id' => 'required|exists:sub_categories,id',
            'image' => 'required|image|mimes:jpg,png,jpeg,gif',
            'summary' => 'nullable|string',
            'description' => 'nullable|string',
            'unit_id' => 'required|exists:units,id',
            'quantity' => 'required|numeric',
            'stock' => 'boolean',
            'status' => 'boolean',
        ]);

        $data = $request->only([
            'name',
            'price',
            'sale_price',
            'sub_category_id',
            'summary',
            'description',
            'unit_id',
            'quantity',
            'stock',
            'status',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images', 'public');
            $url = Storage::url($path);
            $data['image_url'] = $url;
        }

        Product::create(array_merge($data, ['slug' => Str::slug($request->name)]));

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        return view('admin.products.show', compact('product'));
    }


    public function edit(Product $product)
    {
        $subCategories = SubCategory::all();
        $units = Unit::all();
        return view('admin.products.edit', compact('product', 'subCategories', 'units'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'sale_price' => 'nullable|numeric',
            'sub_category_id' => 'required|exists:sub_categories,id',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif',
            'summary' => 'nullable|string',
            'description' => 'nullable|string',
            'unit_id' => 'required|exists:units,id',
            'quantity' => 'required|numeric',
            'stock' => 'boolean',
            'status' => 'boolean',
        ]);

        $data = $request->only([
            'name',
            'price',
            'sale_price',
            'sub_category_id',
            'summary',
            'description',
            'unit_id',
            'quantity',
            'stock',
            'status',
        ]);

        if ($request->hasFile('image')) {
            if ($product->image) {
                $oldImagePath = str_replace('/storage', 'public', $product->image_url);
                Storage::delete($oldImagePath);
            }
    
            $path = $request->file('image')->store('images', 'public');
            $url = Storage::url($path);
            $data['image_url'] = $url;
        }

        $product->update(array_merge($data, ['slug' => Str::slug($request->name)]));

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}