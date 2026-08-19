<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    public function index()
    {
        // Ambil data setting pertama, jika belum ada buatkan instance baru
        $setting = Setting::first() ?? new Setting();
        
        // Jika setting baru dibuat di memori dan belum ada tokennya, coba ambil dari env
        if (!$setting->exists && empty($setting->fonnte_token)) {
            $setting->fonnte_token = env('FONNTE_TOKEN');
        }

        return view('admin.settings.index', compact('setting'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fonnte_token' => 'nullable|string',
            'auto_reminder' => 'nullable|boolean',
        ]);

        $setting = Setting::first();
        
        if (!$setting) {
            $setting = new Setting();
        }

        $setting->fonnte_token = $request->fonnte_token;
        $setting->auto_reminder = $request->has('auto_reminder') ? true : false;
        
        $setting->save();

        return redirect()->route('admin.pengaturan.index')->with('success', 'Pengaturan berhasil disimpan!');
    }
}
