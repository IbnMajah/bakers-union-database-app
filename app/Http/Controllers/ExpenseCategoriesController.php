<?php

namespace App\Http\Controllers;

use App\Models\ExpenseCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class ExpenseCategoriesController extends Controller
{
    public function index()
    {
        return Inertia::render('ExpenseCategories/Index', [
            'filters' => Request::all('search', 'trashed'),
            'expenseCategories' => ExpenseCategory::query()
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
        return Inertia::render('ExpenseCategories/Create');
    }

    public function store()
    {
        Request::validate([
            'name' => ['required', 'max:100'],
            'description' => ['nullable', 'max:255'],
        ]);

        ExpenseCategory::create([
            'name' => Request::get('name'),
            'description' => Request::get('description'),
        ]);

        return Redirect::route('expense-categories')->with('success', 'Expense category created.');
    }

    public function edit(ExpenseCategory $expenseCategory)
    {
        return Inertia::render('ExpenseCategories/Edit', [
            'expenseCategory' => [
                'id' => $expenseCategory->id,
                'name' => $expenseCategory->name,
                'description' => $expenseCategory->description,
                'deleted_at' => $expenseCategory->deleted_at,
            ],
        ]);
    }

    public function update(ExpenseCategory $expenseCategory)
    {
        Request::validate([
            'name' => ['required', 'max:100'],
            'description' => ['nullable', 'max:255'],
        ]);

        $expenseCategory->update([
            'name' => Request::get('name'),
            'description' => Request::get('description'),
        ]);

        return Redirect::route('expense-categories')->with('success', 'Expense category updated.');
    }

    public function destroy(ExpenseCategory $expenseCategory)
    {
        $expenseCategory->delete();

        return Redirect::route('expense-categories')->with('success', 'Expense category deleted.');
    }

    public function restore(ExpenseCategory $expenseCategory)
    {
        $expenseCategory->restore();

        return Redirect::route('expense-categories')->with('success', 'Expense category restored.');
    }
}