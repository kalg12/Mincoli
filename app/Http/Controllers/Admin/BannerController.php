<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::orderBy('position')->get();
        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banners.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'text' => 'required|string|max:500',
            'link_url' => 'nullable|url',
            'position' => 'required|integer|min:1',
            'status' => 'required|in:active,scheduled,inactive',
            'image' => 'nullable|image|mimes:jpeg,png,webp|max:5120',
        ]);

        $validated['is_active'] = $validated['status'] === 'active';
        unset($validated['status']);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('banners', 'public');
            $validated['image_url'] = '/storage/' . $path;
        }

        Banner::create($validated);

        return redirect()
            ->route('dashboard.banners.index')
            ->with('success', 'Banner creado correctamente');
    }

    public function edit($id)
    {
        $banner = Banner::findOrFail($id);
        return view('admin.banners.edit', compact('banner'));
    }

    public function update(Request $request, $id)
    {
        $banner = Banner::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'text' => 'required|string|max:500',
            'link_url' => 'nullable|url',
            'position' => 'required|integer|min:1',
            'status' => 'required|in:active,scheduled,inactive',
            'image' => 'nullable|image|mimes:jpeg,png,webp|max:5120',
        ]);

        $validated['is_active'] = $validated['status'] === 'active';
        unset($validated['status']);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('banners', 'public');
            $validated['image_url'] = '/storage/' . $path;
        }

        $banner->update($validated);

        return redirect()
            ->route('dashboard.banners.index')
            ->with('success', 'Banner actualizado correctamente');
    }

    public function destroy($id)
    {
        $banner = Banner::findOrFail($id);
        $banner->delete();

        return redirect()
            ->route('dashboard.banners.index')
            ->with('success', 'Banner eliminado correctamente');
    }
}
