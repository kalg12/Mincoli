<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::with(['parent'])->withCount('products');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $categories = $query->latest()->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function create(Request $request)
    {
        $parentCategories = Category::whereNull('parent_id')
            ->orWhere('parent_id', 0)
            ->orWhere('parent_id', '')
            ->get();
        return view('admin.categories.create', compact('parentCategories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'status' => 'required|in:active,inactive',
            'image' => 'nullable|image|mimes:jpeg,png,webp|max:5120',
        ]);

        $validated['slug'] = $request->slug ?: Str::slug($validated['name']);
        $validated['is_active'] = $validated['status'] === 'active';
        $validated['parent_id'] = $validated['parent_id'] ?: null;
        unset($validated['status']);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('categories', 'public');
        }

        Category::create($validated);

        return redirect()
            ->route('dashboard.categories.index')
            ->with('success', 'Categoría creada correctamente');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $parentCategories = Category::whereNull('parent_id')
            ->where('id', '!=', $id)
            ->get();
        return view('admin.categories.edit', compact('category', 'parentCategories'));
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'status' => 'required|in:active,inactive',
            'image' => 'nullable|image|mimes:jpeg,png,webp|max:5120',
        ]);

        $validated['slug'] = $request->slug ?: Str::slug($validated['name']);
        $validated['is_active'] = $validated['status'] === 'active';
        $validated['parent_id'] = $validated['parent_id'] ?: null;
        unset($validated['status']);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $validated['image'] = $request->file('image')->store('categories', 'public');
        }

        $category->update($validated);

        return redirect()
            ->route('dashboard.categories.index')
            ->with('success', 'Categoría actualizada correctamente');
    }

    public function toggleActive($id)
    {
        $category = Category::findOrFail($id);
        $category->is_active = !$category->is_active;
        $category->save();

        return back()->with('success', $category->is_active ? 'Categoría activada correctamente' : 'Categoría desactivada correctamente');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        if ($category->products()->count() > 0) {
            return back()->with('error', 'No se puede eliminar una categoría con productos');
        }

        $category->delete();

        return redirect()
            ->route('dashboard.categories.index')
            ->with('success', 'Categoría movida a la papelera');
    }

    public function trash()
    {
        $categories = Category::onlyTrashed()->withCount('products')->latest('deleted_at')->get();
        return view('admin.categories.trash', compact('categories'));
    }

    public function restore($id)
    {
        $category = Category::onlyTrashed()->findOrFail($id);
        $category->restore();

        return redirect()
            ->route('dashboard.categories.trash')
            ->with('success', 'Categoría restaurada correctamente');
    }

    public function forceDelete($id)
    {
        $category = Category::onlyTrashed()->findOrFail($id);
        $category->forceDelete();

        return redirect()
            ->route('dashboard.categories.trash')
            ->with('success', 'Categoría eliminada permanentemente');
    }
}
