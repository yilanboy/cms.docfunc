<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\ToastType;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::orderBy('id')
            ->when($request->query('search'), function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->paginate(10)
            ->withQueryString()
            ->onEachSide(1);

        $categories = CategoryResource::collection($categories);

        return Inertia::render('categories/Page', [
            'title' => 'Categories',
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'icon' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
        ]);

        Category::create($validated);

        $lastPage = Category::paginate(10)->lastPage();

        Inertia::flash('toast', [
            'type' => ToastType::SUCCESS,
            'message' => 'Category created successfully.',
        ]);

        return to_route('categories.index', ['page' => $lastPage]);
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'icon' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
        ]);

        $category->update($validated);

        return Inertia::flash('toast', [
            'type' => ToastType::SUCCESS,
            'message' => 'Category updated successfully.',
        ])->back();
    }

    public function destroy(Category $category)
    {
        if ($category->is_default) {
            return Inertia::flash('toast', [
                'type' => ToastType::DANGER,
                'message' => 'Default category cannot be deleted.',
            ])->back();
        }

        $category->delete();

        return Inertia::flash('toast', [
            'type' => ToastType::SUCCESS,
            'message' => 'Category deleted successfully.',
        ])->back();
    }
}
