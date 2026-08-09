<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SizeController extends Controller
{
    public function index()
    {
        $sizes = Size::latest()->paginate(20);
        return view('admin.sizes.index', compact('sizes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50',
        ]);

        Size::create([
            'name' => $request->name,
            'code' => $request->code,
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'sizes' => Size::all()]);
        }

        return back()->with('success', 'Custom size / storage option created successfully!');
    }

    public function destroy($id)
    {
        Size::findOrFail($id)->delete();
        return back()->with('success', 'Size option deleted.');
    }
}
