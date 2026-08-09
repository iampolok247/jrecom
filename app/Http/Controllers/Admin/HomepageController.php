<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\HomepageSection;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HomepageController extends Controller
{
    public function index()
    {
        $sections = HomepageSection::orderBy('order', 'asc')->get();
        $sliders = Banner::where('section', 'hero_slider')->orderBy('order', 'asc')->get();
        $offerBanners = Banner::where('section', 'offer_banner')->orderBy('order', 'asc')->get();

        return view('admin.homepage.index', compact('sections', 'sliders', 'offerBanners'));
    }

    public function updateSections(Request $request)
    {
        if ($request->has('sections')) {
            foreach ($request->sections as $id => $data) {
                $sec = HomepageSection::find($id);
                if ($sec) {
                    $sec->update([
                        'title' => $data['title'] ?? $sec->title,
                        'is_enabled' => isset($data['is_enabled']),
                        'order' => $data['order'] ?? $sec->order,
                    ]);
                }
            }
        }
        return back()->with('success', 'Homepage layout and section order saved!');
    }

    public function storeBanner(Request $request)
    {
        $request->validate([
            'section' => 'required|string',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:4096',
        ]);

        $imgPath = $request->image;
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $fileName = time() . '_banner_' . Str::random(4) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('banners', $fileName, 'public');
            $imgPath = asset('storage/' . $path);
        }

        if (empty($imgPath)) {
            return back()->with('error', 'Please provide a banner image file or image URL.');
        }

        Banner::create([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'image' => $imgPath,
            'link' => $request->link,
            'button_text' => $request->button_text,
            'section' => $request->section,
            'status' => true,
            'order' => Banner::where('section', $request->section)->count() + 1,
        ]);

        return back()->with('success', 'Banner created successfully!');
    }

    public function destroyBanner($id)
    {
        Banner::findOrFail($id)->delete();
        return back()->with('success', 'Banner deleted.');
    }
}
