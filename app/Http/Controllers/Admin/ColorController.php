<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Color;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ColorController extends Controller
{
    public function index()
    {
        $colors = Color::latest()->paginate(20);
        return view('admin.colors.index', compact('colors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50',
        ]);

        $code = Str::startsWith($request->code, '#') ? $request->code : ('#' . $request->code);

        Color::create([
            'name' => $request->name,
            'code' => $code,
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'colors' => Color::all()]);
        }

        return back()->with('success', 'Custom color added successfully!');
    }

    public function destroy($id)
    {
        Color::findOrFail($id)->delete();
        return back()->with('success', 'Color deleted.');
    }
}
