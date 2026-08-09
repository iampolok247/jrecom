<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('parent')->latest()->paginate(15);
        $parentCategories = Category::where('level', 0)->get();
        return view('admin.categories.index', compact('categories', 'parentCategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:4096',
        ]);

        $imgPath = $request->image;
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $fileName = time() . '_cat_' . Str::random(4) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('categories', $fileName, 'public');
            $imgPath = asset('storage/' . $path);
        }

        $parent = $request->parent_id ? Category::find($request->parent_id) : null;
        $level = $parent ? ($parent->level + 1) : 0;

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'icon' => $request->icon ?? 'bi-tag',
            'image' => $imgPath,
            'parent_id' => $request->parent_id ?: null,
            'level' => $level,
            'is_featured' => $request->boolean('is_featured'),
            'status' => $request->boolean('status', true),
        ]);

        return back()->with('success', 'Category created successfully!');
    }

    public function destroy($id)
    {
        Category::findOrFail($id)->delete();
        return back()->with('success', 'Category deleted.');
    }
}
