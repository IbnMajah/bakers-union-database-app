<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class CategoriesController extends Controller
{
    public function index()
    {
        return Inertia::render('Categories/Index', [
            'filters' => Request::all('search', 'trashed'),
            'categories' => Auth::user()->account->categories()
                ->orderBy('name')
                ->filter(Request::only('search', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($category) => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'description' => $category->description,
                    'deleted_at' => $category->deleted_at
                ]),
        ]);
    }

    public function create()
    {
        return Inertia::render('Categories/Create');
    }

    public function store()
    {
        Request::validate([
            'name' => ['required', 'max:100'],
            'description' => ['nullable', 'max:255'],
        ]);

        Auth::user()->account->categories()->create([
            'name' => Request::get('name'),
            'description' => Request::get('description'),
        ]);

        return Redirect::route('categories.index')->with('success', 'Category created.');
    }

    public function edit(Category $category)
    {
        return Inertia::render('Categories/Edit', [
            'category' => [
                'id' => $category->id,
                'name' => $category->name,
                'description' => $category->description,
                'deleted_at' => $category->deleted_at,
            ],
        ]);
    }

    public function update(Category $category)
    {
        Request::validate([
            'name' => ['required', 'max:100'],
            'description' => ['nullable', 'max:255'],
        ]);

        $category->update([
            'name' => Request::get('name'),
            'description' => Request::get('description'),
        ]);

        return Redirect::back()->with('success', 'Category updated.');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return Redirect::back()->with('success', 'Category deleted.');
    }

    public function restore(Category $category)
    {
        $category->restore();

        return Redirect::back()->with('success', 'Category restored.');
    }
}