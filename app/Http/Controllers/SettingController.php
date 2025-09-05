<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    /**
     * Display the settings page.
     */
    public function index()
    {
        $settings = [
            'site_name' => Setting::where('setting_key', 'site_name')->value('setting_value') ?? 'Boomtale',
            'site_description' => Setting::where('setting_key', 'site_description')->value('setting_value') ?? 'Platform digital marketplace terpercaya',
            'site_email' => Setting::where('setting_key', 'site_email')->value('setting_value') ?? 'admin@boomtale.com',
            'site_phone' => Setting::where('setting_key', 'site_phone')->value('setting_value') ?? '+62 123 456 7890',
        ];

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update general settings.
     */
    public function updateGeneral(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_description' => 'required|string|max:500',
            'site_email' => 'required|email',
            'site_phone' => 'nullable|string|max:20',
        ]);

        $settings = [
            'site_name' => $request->site_name,
            'site_description' => $request->site_description,
            'site_email' => $request->site_email,
            'site_phone' => $request->site_phone,
        ];

        foreach ($settings as $key => $value) {
            \App\Models\Setting::updateOrCreate(
                ['setting_key' => $key],
                ['setting_value' => $value]
            );
        }

        return back()->with('success', 'Pengaturan umum berhasil disimpan.');
    }
}
