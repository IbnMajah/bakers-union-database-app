<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Bakery;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class AccountsController extends Controller
{
    public function index()
    {
        return Inertia::render('Accounts/Index', [
            'filters' => Request::all('search', 'trashed'),
            'accounts' => Account::with('bakery')
                ->orderBy('name')
                ->filter(Request::only('search', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($account) => [
                    'id' => $account->id,
                    'name' => $account->name,
                    'bakery' => $account->bakery ? $account->bakery->name : null,
                    'balance' => $account->balance,
                    'is_general' => $account->is_general,
                    'last_transaction' => $account->last_transaction,
                    'deleted_at' => $account->deleted_at
                ]),
        ]);
    }

    public function create()
    {
        return Inertia::render('Accounts/Create', [
            'bakeries' => Bakery::orderBy('name')
                ->get()
                ->map
                ->only('id', 'name'),
        ]);
    }

    public function store()
    {
        Request::validate([
            'name' => ['required', 'max:100'],
            'bakery_id' => ['required', 'exists:bakeries,id'],
        ]);

        Account::create([
            'name' => Request::get('name'),
            'bakery_id' => Request::get('bakery_id'),
        ]);

        return Redirect::route('accounts')->with('success', 'Account created.');
    }

    public function edit(Account $account)
    {
        return Inertia::render('Accounts/Edit', [
            'account' => [
                'id' => $account->id,
                'name' => $account->name,
                'bakery_id' => $account->bakery_id,
                'balance' => $account->balance,
                'last_transaction' => $account->last_transaction,
                'deleted_at' => $account->deleted_at,
            ],
            'bakeries' => Bakery::orderBy('name')
                ->get()
                ->map
                ->only('id', 'name'),
        ]);
    }

    public function update(Account $account)
    {
        Request::validate([
            'name' => ['required', 'max:100'],
            'bakery_id' => ['required', 'exists:bakeries,id'],
        ]);

        $account->update(Request::only('name', 'bakery_id'));

        return Redirect::back()->with('success', 'Account updated.');
    }

    public function destroy(Account $account)
    {
        if ($account->is_general) {
            return Redirect::back()->with('error', 'Cannot delete general account.');
        }

        $account->delete();

        return Redirect::back()->with('success', 'Account deleted.');
    }

    public function restore(Account $account)
    {
        $account->restore();

        return Redirect::back()->with('success', 'Account restored.');
    }
}