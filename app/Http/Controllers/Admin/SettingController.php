<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::all()->pluck('value', 'key');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'site_logo_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg,ico|max:4096',
            'site_dark_logo_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg,ico|max:4096',
            'site_favicon_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg,ico|max:4096',
        ]);

        $keys = [
            'site_name',
            'site_title',
            'site_tagline',
            'primary_color',
            'secondary_color',
            'accent_color',
            'typography',
            'site_currency',
            'currency_symbol',
            'support_phone',
            'whatsapp_phone',
            'support_email',
            'office_address',
            'facebook_url',
            'instagram_url',
            'youtube_url',
            'twitter_url',
            'seo_meta_title',
            'seo_meta_description',
            'seo_meta_keywords',
            'footer_copyright',
            'footer_description',
        ];

        foreach ($keys as $k) {
            if ($request->has($k)) {
                SiteSetting::setKey($k, $request->input($k));
            }
        }

        // Process Site Logo (File Upload takes priority over text URL)
        if ($request->hasFile('site_logo_file')) {
            $file = $request->file('site_logo_file');
            $fileName = 'logo_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('settings', $fileName, 'public');
            SiteSetting::setKey('site_logo', asset('storage/' . $path));
        } elseif ($request->filled('site_logo')) {
            SiteSetting::setKey('site_logo', $request->input('site_logo'));
        }

        // Process Dark Logo
        if ($request->hasFile('site_dark_logo_file')) {
            $file = $request->file('site_dark_logo_file');
            $fileName = 'dark_logo_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('settings', $fileName, 'public');
            SiteSetting::setKey('site_dark_logo', asset('storage/' . $path));
        } elseif ($request->filled('site_dark_logo')) {
            SiteSetting::setKey('site_dark_logo', $request->input('site_dark_logo'));
        }

        // Process Favicon (File Upload takes priority over text URL)
        if ($request->hasFile('site_favicon_file')) {
            $file = $request->file('site_favicon_file');
            $fileName = 'favicon_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('settings', $fileName, 'public');
            SiteSetting::setKey('site_favicon', asset('storage/' . $path));
        } elseif ($request->filled('site_favicon')) {
            SiteSetting::setKey('site_favicon', $request->input('site_favicon'));
        }

        return back()->with('success', 'Site settings, logos, and favicon updated successfully!');
    }
}
