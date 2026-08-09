<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::latest()->paginate(15);
        return view('admin.brands.index', compact('brands'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:4096',
        ]);

        $logoPath = $request->logo;
        if ($request->hasFile('logo_file')) {
            $file = $request->file('logo_file');
            $fileName = time() . '_brand_' . Str::random(4) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('brands', $fileName, 'public');
            $logoPath = asset('storage/' . $path);
        }

        Brand::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'logo' => $logoPath,
            'is_featured' => $request->boolean('is_featured'),
            'status' => $request->boolean('status', true),
        ]);

        return back()->with('success', 'Brand added successfully.');
    }

    public function destroy($id)
    {
        Brand::findOrFail($id)->delete();
        return back()->with('success', 'Brand deleted.');
    }
}
