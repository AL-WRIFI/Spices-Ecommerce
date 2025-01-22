<?php

namespace App\Http\Controllers\Admin\Categories;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();
        
        if($request->wantsJson()) {
            return response()->json([
                'categories' => $categories,
            ], 200);
        }
        return view('admin.categories.category.index', compact('categories'));
    }


    public function create(Request $request) {
        return view('admin.categories.category.create');
    }


    public function store(Request $request) {

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpg,png,jpeg,gif',
        ]);

        $data = $request->only(['name','description']);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images', 'public');
            $url = Storage::url($path);
            $data['image'] = $url;
        }

        $category = Category::create(array_merge($data, ['slug' => Str::slug($data['name'])]));

        return redirect()->route('categories.index');
    }

    public function edit(Category $category) {
        return view('admin.categories.category.edit', compact('category'));
    }
    
    public function update(Request $request, Category $category) {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif',
        ]);
    
        $data = $request->only(['name', 'description']);
    
        if ($request->hasFile('image')) {
            if ($category->image) {
                $oldImagePath = str_replace('/storage', 'public', $category->image);
                Storage::delete($oldImagePath);
            }
    
            $path = $request->file('image')->store('images', 'public');
            $url = Storage::url($path);
            $data['image'] = $url;
        }
    
        $category->update(array_merge($data, ['slug' => Str::slug($data['name'])]));
    
        return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
    }
}
