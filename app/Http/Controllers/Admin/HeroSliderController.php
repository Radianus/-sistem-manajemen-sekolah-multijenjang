<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HeroSlider;
use Illuminate\Support\Facades\Storage;

class HeroSliderController extends Controller
{
    /**
     * Display a listing of the hero sliders.
     */
    public function index()
    {
        abort_if(!auth()->user()->hasRole('admin_sekolah'), 403);
        $sliders = HeroSlider::orderBy('order')->paginate(10);
        return view('admin.hero_sliders.index', compact('sliders'));
    }

    /**
     * Show the form for creating a new hero slider.
     */
    public function create()
    {
        abort_if(!auth()->user()->hasRole('admin_sekolah'), 403);
        return view('admin.hero_sliders.create');
    }

    /**
     * Store a newly created hero slider in storage.
     */
    public function store(Request $request)
    {
        abort_if(!auth()->user()->hasRole('admin_sekolah'), 403);
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'link_url' => ['nullable', 'url', 'max:255'],
            'order' => ['nullable', 'integer'],
            'is_active' => ['boolean'],
        ]);
        $imagePath = $request->file('image')->store('hero_sliders', 'public');

        HeroSlider::create([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'image_path' => $imagePath,
            'link_url' => $request->link_url,
            'order' => $request->order ?? 0,
            'is_active' => $request->is_active ?? false,
        ]);

        return redirect()->route('admin.hero_sliders.index')->with('success', 'Slider berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified hero slider.
     */
    public function edit(HeroSlider $slider)
    {
        abort_if(!auth()->user()->hasRole('admin_sekolah'), 403);
        return view('admin.hero_sliders.edit', compact('slider'));
    }

    /**
     * Update the specified hero slider in storage.
     */
    public function update(Request $request, HeroSlider $slider)
    {
        abort_if(!auth()->user()->hasRole('admin_sekolah'), 403);
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'link_url' => ['nullable', 'url', 'max:255'],
            'order' => ['nullable', 'integer'],
            'is_active' => ['boolean'],
        ]);

        $imagePath = $slider->image_path;
        if ($request->hasFile('image')) {
            if ($imagePath && Storage::disk('public')->exists(str_replace('storage/', 'public/', $imagePath))) {
                Storage::disk('public')->delete(str_replace('storage/', 'public/', $imagePath));
            }
            $imagePath = $request->file('image')->store('hero_sliders', 'public');
        }

        $slider->update([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'image_path' => $imagePath,
            'link_url' => $request->link_url,
            'order' => $request->order ?? 0,
            'is_active' => $request->is_active ?? false,
        ]);

        return redirect()->route('admin.hero_sliders.index')->with('success', 'Slider berhasil diperbarui.');
    }

    /**
     * Remove the specified hero slider from storage.
     */
    public function destroy(HeroSlider $slider)
    {
        abort_if(!auth()->user()->hasRole('admin_sekolah'), 403);
        if ($slider->image_path && Storage::disk('public')->exists(str_replace('storage/', 'public/', $slider->image_path))) {
            Storage::disk('public')->delete(str_replace('storage/', 'public/', $slider->image_path));
        }
        $slider->delete();
        return redirect()->route('admin.hero_sliders.index')->with('success', 'Slider berhasil dihapus.');
    }
}