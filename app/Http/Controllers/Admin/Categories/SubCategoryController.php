<?php

namespace App\Http\Controllers\Admin\Categories;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SubCategoryController extends Controller
{
    public function index(Request $request)
    {
        $subCategories = SubCategory::all();
        
        if($request->wantsJson()) {
            return response()->json([
                'sub_categories' => $subCategories
            ], 200);
        }
        return view('admin.categories.subCategory.index', compact('subCategories'));
    }

    public function create(Request $request) {
        $categories = Category::all();
        return view('admin.categories.subCategory.create', compact('categories'));
    }


    public function store(Request $request) {

        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpg,png,jpeg,gif',
        ]);

        $data = $request->only(['name', 'category_id', 'description']);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images', 'public');
            $url = Storage::url($path);
            $data['image'] = $url;
        }

        $subCategory = SubCategory::create(array_merge($data, ['slug' => Str::slug($data['name'])]));

        return redirect()->route('sub-categories.index')->with('success', 'Sub Category created successfully.');
    }

    public function edit(SubCategory $subCategory) {
        $categories = Category::all();
        return view('admin.categories.subCategory.edit', compact('subCategory', 'categories'));
    }
    
    public function update(Request $request, SubCategory $subCategory) {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif',
        ]);
    
        $data = $request->only(['name', 'description', 'category_id']);
    
        if ($request->hasFile('image')) {
            if ($subCategory->image) {
                $oldImagePath = str_replace('/storage', 'public', $subCategory->image);
                Storage::delete($oldImagePath);
            }
    
            $path = $request->file('image')->store('images', 'public');
            $url = Storage::url($path);
            $data['image'] = $url;
        }
    
        $subCategory->update(array_merge($data, ['slug' => Str::slug($data['name'])]));
    
        return redirect()->route('sub-categories.index')->with('success', 'Sub Category updated successfully.');
    }
}
