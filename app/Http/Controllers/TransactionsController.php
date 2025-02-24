<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use App\Models\Bakery;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class TransactionsController extends Controller
{
    public function index()
    {
        return Inertia::render('Transactions/Index', [
            'filters' => Request::all('search', 'trashed', 'bakery_id', 'account_id'),
            'transactions' => Transaction::with('account.bakery')
                ->whereHas('account', function ($query) {
                    $query->whereNotNull('bakery_id');
                })
                ->orderByDesc('transaction_date')
                ->filter(Request::only('search', 'trashed', 'bakery_id', 'account_id'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($transaction) => [
                    'id' => $transaction->id,
                    'account' => $transaction->account->name,
                    'bakery' => $transaction->account->bakery->name,
                    'type' => $transaction->type,
                    'amount' => $transaction->amount,
                    'description' => $transaction->description,
                    'transaction_date' => $transaction->transaction_date->format('d/m/Y'),
                    'deleted_at' => $transaction->deleted_at,
                ]),
            'bakeries' => Bakery::orderBy('name')
                ->get()
                ->map
                ->only('id', 'name'),
            'accounts' => Account::whereNotNull('bakery_id')
                ->orderBy('name')
                ->get()
                ->map(fn ($account) => [
                    'id' => $account->id,
                    'name' => $account->name . ' (' . $account->bakery->name . ')',
                ]),
        ]);
    }

    public function create()
    {
        return Inertia::render('Transactions/Create', [
            'accounts' => Account::orderBy('name')
                ->get()
                ->map(fn ($account) => [
                    'id' => $account->id,
                    'name' => $account->bakery
                        ? $account->name . ' (' . $account->bakery->name . ')'
                        : $account->name . ' (General Account)',
                ]),
        ]);
    }

    public function store()
    {
        Request::validate([
            'account_id' => ['required', 'exists:accounts,id'],
            'type' => ['required', 'in:credit,debit'],
            'amount' => ['required', 'numeric', 'min:0'],
            'description' => ['required', 'max:255'],
            'transaction_date' => ['required', 'date'],
        ]);

        Transaction::create([
            'account_id' => Request::get('account_id'),
            'type' => Request::get('type'),
            'amount' => Request::get('amount'),
            'description' => Request::get('description'),
            'transaction_date' => Request::get('transaction_date'),
        ]);

        return Redirect::route('transactions.index')->with('success', 'Transaction created.');
    }

    public function edit(Transaction $transaction)
    {
        return Inertia::render('Transactions/Edit', [
            'transaction' => [
                'id' => $transaction->id,
                'account_id' => $transaction->account_id,
                'type' => $transaction->type,
                'amount' => $transaction->amount,
                'description' => $transaction->description,
                'transaction_date' => $transaction->transaction_date->format('Y-m-d'),
                'deleted_at' => $transaction->deleted_at,
            ],
            'accounts' => Account::orderBy('name')
                ->get()
                ->map(fn ($account) => [
                    'id' => $account->id,
                    'name' => $account->bakery
                        ? $account->name . ' (' . $account->bakery->name . ')'
                        : $account->name . ' (General Account)',
                ]),
        ]);
    }

    public function update(Transaction $transaction)
    {
        Request::validate([
            'account_id' => ['required', 'exists:accounts,id'],
            'type' => ['required', 'in:credit,debit'],
            'amount' => ['required', 'numeric', 'min:0'],
            'description' => ['required', 'max:255'],
            'transaction_date' => ['required', 'date'],
        ]);

        $transaction->update(Request::only('account_id', 'type', 'amount', 'description', 'transaction_date'));

        return Redirect::back()->with('success', 'Transaction updated.');
    }

    public function destroy(Transaction $transaction)
    {
        $transaction->delete();

        return Redirect::back()->with('success', 'Transaction deleted.');
    }

    public function restore(Transaction $transaction)
    {
        $transaction->restore();

        return Redirect::back()->with('success', 'Transaction restored.');
    }
}