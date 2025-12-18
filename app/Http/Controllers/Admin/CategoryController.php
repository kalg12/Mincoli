<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->latest()->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $validated['status'] === 'active';

        Category::create($validated);

        return redirect()
            ->route('dashboard.categories.index')
            ->with('success', 'Categoría creada correctamente');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $validated['status'] === 'active';
        unset($validated['status']);

        $category->update($validated);

        return redirect()
            ->route('dashboard.categories.index')
            ->with('success', 'Categoría actualizada correctamente');
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
