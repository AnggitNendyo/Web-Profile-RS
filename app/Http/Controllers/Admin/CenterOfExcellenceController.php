<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CenterOfExcellence;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CenterOfExcellenceController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Admin/Coes/Index', [
            'coes' => CenterOfExcellence::when($request->search, function ($query, $search) {
                    $query->where('title', 'ilike', "%{$search}%")
                        ->orWhere('short_description', 'ilike', "%{$search}%");
                })
                ->paginate(10)
                ->withQueryString(),
            'filters' => ['search' => $request->search],
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Coes/Form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'required|string',
            'full_content' => 'required|string',
            'technology_used' => 'nullable|array',
            'slider_images' => 'nullable|array',
            'slider_images.*' => 'image|max:2048',
            'show_slider' => 'boolean',
            'banner_image' => 'nullable|image|max:2048',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['technology_used'] = $validated['technology_used'] ?? [];
        $validated['show_slider'] = $request->boolean('show_slider', true);
        
        $images = [];
        if ($request->hasFile('slider_images')) {
            foreach ($request->file('slider_images') as $file) {
                $path = $file->store('coe_sliders', 'public');
                $images[] = $path;
            }
        }
        $validated['slider_images'] = $images;

        if ($request->hasFile('banner_image')) {
            $validated['banner_image'] = $request->file('banner_image')->store('coe_banners', 'public');
        }

        CenterOfExcellence::create($validated);

        return redirect()->route('admin.coes.index')
            ->with('success', 'Layanan Unggulan berhasil ditambahkan.');
    }

    public function edit(CenterOfExcellence $coe)
    {
        return Inertia::render('Admin/Coes/Form', [
            'coe' => $coe
        ]);
    }

    public function update(Request $request, CenterOfExcellence $coe)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'required|string',
            'full_content' => 'required|string',
            'technology_used' => 'nullable|array',
            'slider_images' => 'nullable|array',
            'slider_images.*' => 'image|max:2048',
            'existing_images' => 'nullable|array',
            'show_slider' => 'boolean',
            'banner_image' => 'nullable|image|max:2048',
        ]);

        if ($request->title !== $coe->title) {
            $validated['slug'] = Str::slug($validated['title']);
        }
        $validated['technology_used'] = $validated['technology_used'] ?? $coe->technology_used;
        $validated['show_slider'] = $request->boolean('show_slider', true);

        // Handle image updates
        $images = $request->input('existing_images', []);
        
        // Delete removed images from storage
        $oldImages = $coe->slider_images ?? [];
        $removedImages = array_diff($oldImages, $images);
        foreach ($removedImages as $removedImage) {
            Storage::disk('public')->delete($removedImage);
        }

        // Add new uploaded images
        if ($request->hasFile('slider_images')) {
            foreach ($request->file('slider_images') as $file) {
                $images[] = $file->store('coe_sliders', 'public');
            }
        }
        
        $validated['slider_images'] = $images;

        if ($request->hasFile('banner_image')) {
            if ($coe->banner_image) {
                Storage::disk('public')->delete($coe->banner_image);
            }
            $validated['banner_image'] = $request->file('banner_image')->store('coe_banners', 'public');
        } else {
            unset($validated['banner_image']);
        }

        $coe->update($validated);

        return redirect()->route('admin.coes.index')->with('success', 'Layanan unggulan berhasil diperbarui.');
    }

    public function destroy(CenterOfExcellence $coe)
    {
        // Delete all images when CoE is deleted
        if (!empty($coe->slider_images)) {
            foreach ($coe->slider_images as $image) {
                Storage::disk('public')->delete($image);
            }
        }
        if ($coe->banner_image) {
            Storage::disk('public')->delete($coe->banner_image);
        }
        
        $coe->delete();
        return redirect()->route('admin.coes.index')->with('success', 'Layanan unggulan berhasil dihapus.');
    }
}
