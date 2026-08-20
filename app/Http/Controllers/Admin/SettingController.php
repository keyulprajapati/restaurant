<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    /**
     * Display the general settings form.
     */
    public function index()
    {
        $settings = [
            'site_name' => Setting::get('site_name', 'Restaurant System'),
            'brand_logo' => Setting::get('brand_logo'),
            'favicon_icon' => Setting::get('favicon_icon'),
            'site_icon' => Setting::get('site_icon'),
        ];

        return view('admin.settings.general', compact('settings'));
    }

    /**
     * Update the general settings.
     */
    public function updateGeneral(Request $request)
    {
        $validated = $request->validate([
            'site_name' => ['required', 'string', 'max:255'],
            'brand_logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:2048'],
            'favicon_icon' => ['nullable', 'file', 'mimes:ico,png,jpg,jpeg,svg', 'max:1024'],
            'site_icon' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:2048'],
        ]);

        // Save site_name
        Setting::set('site_name', $validated['site_name']);

        // Handle Brand Logo Upload
        if ($request->hasFile('brand_logo')) {
            $this->deleteOldFile('brand_logo');
            $path = $request->file('brand_logo')->store('settings', 'public');
            Setting::set('brand_logo', 'storage/' . $path);
        }

        // Handle Favicon Icon Upload
        if ($request->hasFile('favicon_icon')) {
            $this->deleteOldFile('favicon_icon');
            $path = $request->file('favicon_icon')->store('settings', 'public');
            Setting::set('favicon_icon', 'storage/' . $path);
        }

        // Handle Site Icon Upload
        if ($request->hasFile('site_icon')) {
            $this->deleteOldFile('site_icon');
            $path = $request->file('site_icon')->store('settings', 'public');
            Setting::set('site_icon', 'storage/' . $path);
        }

        return redirect()
            ->route('admin.settings.general')
            ->with('success', 'General settings updated successfully.');
    }

    /**
     * Display the theme settings form.
     */
    public function theme()
    {
        $theme = [
            'primary_color' => Setting::get('theme_primary_color', '#23422A'),
            'secondary_color' => Setting::get('theme_secondary_color', '#C69A39'),
            'background_color' => Setting::get('theme_background_color', '#F9F6EE'),
            'sidebar_color' => Setting::get('theme_sidebar_color', '#111F15'),
            'font_color' => Setting::get('theme_font_color', '#1C2E20'),
            'font_family' => Setting::get('theme_font_family', 'Outfit'),
        ];

        return view('admin.settings.theme', compact('theme'));
    }

    /**
     * Update the theme settings.
     */
    public function updateTheme(Request $request)
    {
        $validated = $request->validate([
            'primary_color' => ['required', 'string', 'regex:/^#([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$/'],
            'secondary_color' => ['required', 'string', 'regex:/^#([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$/'],
            'background_color' => ['required', 'string', 'regex:/^#([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$/'],
            'sidebar_color' => ['required', 'string', 'regex:/^#([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$/'],
            'font_color' => ['required', 'string', 'regex:/^#([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$/'],
            'font_family' => ['required', 'string', 'max:100'],
        ]);

        Setting::set('theme_primary_color', $validated['primary_color'], 'theme');
        Setting::set('theme_secondary_color', $validated['secondary_color'], 'theme');
        Setting::set('theme_background_color', $validated['background_color'], 'theme');
        Setting::set('theme_sidebar_color', $validated['sidebar_color'], 'theme');
        Setting::set('theme_font_color', $validated['font_color'], 'theme');
        Setting::set('theme_font_family', $validated['font_family'], 'theme');

        return redirect()
            ->route('admin.settings.theme')
            ->with('success', 'Theme settings updated successfully.');
    }

    /**
     * Helper to delete old setting file from public disk.
     */
    protected function deleteOldFile(string $key): void
    {
        $oldPath = Setting::get($key);
        if ($oldPath && str_starts_with($oldPath, 'storage/')) {
            $relativePath = str_replace('storage/', '', $oldPath);
            if (Storage::disk('public')->exists($relativePath)) {
                Storage::disk('public')->delete($relativePath);
            }
        }
    }
}
