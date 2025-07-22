<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Validation\Rule;

class SettingController extends Controller
{
    /**
     * Show the form for editing/viewing settings.
     * There should only be one settings record (ID 1).
     */
    public function edit()
    {
        // Hanya Admin yang bisa mengakses pengaturan sistem
        abort_if(!auth()->user()->hasRole('admin_sekolah'), 403);

        $setting = Setting::firstOrCreate(['id' => 1]); // Pastikan ada record pengaturan (ID 1)
        return view('admin.settings.edit', compact('setting'));
    }

    /**
     * Update the settings in storage.
     */
    public function update(Request $request)
    {
        abort_if(!auth()->user()->hasRole('admin_sekolah'), 403);

        $request->validate([
            'school_name' => ['required', 'string', 'max:255'],
            'school_address' => ['nullable', 'string'],
            'school_phone' => ['nullable', 'string', 'max:50'],
            'school_email' => ['nullable', 'string', 'email', 'max:255'],
            'current_academic_year' => ['nullable', 'string', 'max:20'],
            // 'logo_path' => ['nullable', 'image', 'max:2048'], // Jika ingin upload gambar logo
        ]);

        $setting = Setting::firstOrCreate(['id' => 1]); // Pastikan ada record pengaturan

        // Handle file upload if you add logo_path to the form
        // if ($request->hasFile('logo_path')) {
        //     $path = $request->file('logo_path')->store('public/logos');
        //     $setting->logo_path = str_replace('public/', 'storage/', $path);
        // }

        $setting->update($request->except('logo_path')); // Update semua kecuali logo_path jika tidak di-handle di atas

        return redirect()->back()->with('success', 'Pengaturan sistem berhasil diperbarui.');
    }
}
