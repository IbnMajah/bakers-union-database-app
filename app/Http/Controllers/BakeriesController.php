<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Bakery;
use App\Models\Category;
use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;

class BakeriesController extends Controller
{
    public function index()
    {
        return Inertia::render('Bakeries/Index', [
            'filters' => Request::all('search', 'trashed'),
            'bakeries' => Bakery::with('category')
                ->orderBy('name')
                ->filter(Request::only('search', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($bakery) => [
                    'id' => $bakery->id,
                    'name' => $bakery->name,
                    'address' => $bakery->address,
                    'category' => $bakery->category ? $bakery->category->name : null,
                    'contact_person' => $bakery->contact_person,
                    'phone' => $bakery->phone,
                    'email' => $bakery->email,
                    'status' => $bakery->status,
                    'deleted_at' => $bakery->deleted_at,
                    'last_payment' => $bakery->last_payment,
                ]),
        ]);
    }

    public function create()
    {
        return Inertia::render('Bakeries/Create', [
            'categories' => Category::orderBy('name')
                ->get()
                ->map
                ->only('id', 'name'),
        ]);
    }

    public function store()
    {
        Request::validate([
            'name' => ['required', 'max:100'],
            'address' => ['required', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'contact_person' => ['required', 'max:100'],
            'phone' => ['required', 'max:50'],
            'email' => ['required', 'email', 'max:50'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        Bakery::create([
            'name' => Request::get('name'),
            'address' => Request::get('address'),
            'category_id' => Request::get('category_id'),
            'contact_person' => Request::get('contact_person'),
            'phone' => Request::get('phone'),
            'email' => Request::get('email'),
            'status' => Request::get('status'),
        ]);

        return Redirect::route('bakeries')->with('success', 'Bakery created.');
    }

    public function edit(Bakery $bakery)
    {
        return Inertia::render('Bakeries/Edit', [
            'bakery' => [
                'id' => $bakery->id,
                'name' => $bakery->name,
                'category_id' => $bakery->category_id,
                'contact_person' => $bakery->contact_person,
                'phone' => $bakery->phone,
                'email' => $bakery->email,
                'address' => $bakery->address,
                'status' => $bakery->status,
                'last_payment' => $bakery->last_payment,
                'deleted_at' => $bakery->deleted_at,
            ],
            'categories' => Category::orderBy('name')
                ->get()
                ->map
                ->only('id', 'name'),
            'bakeryAccount' => $bakery->account,
            'generalAccount' => Account::where('is_general', true)->first(),
            'transactions' => Transaction::whereHas('account', function ($query) use ($bakery) {
                    $query->where('bakery_id', $bakery->id);
                })
                ->orderByDesc('transaction_date')
                ->take(10)
                ->get()
                ->map(fn ($transaction) => [
                    'id' => $transaction->id,
                    'amount' => $transaction->amount,
                    'type' => $transaction->type,
                    'description' => $transaction->description,
                    'transaction_date' => $transaction->transaction_date->format('d/m/Y'),
                ]),
        ]);
    }

    public function update(Bakery $bakery)
    {
        Request::validate([
            'name' => ['required', 'max:100'],
            'address' => ['required', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'contact_person' => ['required', 'max:100'],
            'phone' => ['required', 'max:50'],
            'email' => ['required', 'email', 'max:50'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $bakery->update(Request::only('name', 'address', 'category_id', 'contact_person', 'phone', 'email', 'status'));

        return Redirect::route('bakeries')->with('success', 'Bakery updated.');
    }

    public function destroy(Bakery $bakery)
    {
        $bakery->delete();

        return Redirect::route('bakeries')->with('success', 'Bakery deleted.');
    }

    public function restore(Bakery $bakery)
    {
        $bakery->restore();

        return Redirect::route('bakeries')->with('success', 'Bakery restored.');
    }

    public function recordPayment(Bakery $bakery)
    {
        Request::validate([
            'amount' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        $amount = Request::get('amount');
        $date = now();
        $description = Request::get('description') ?: "Payment from {$bakery->name}";

        // Get or create bakery account
        $bakeryAccount = $bakery->account ?? Account::create([
            'name' => $bakery->name . ' Account',
            'bakery_id' => $bakery->id,
            'balance' => 0, // Initialize balance if creating new account
        ]);

        // Get general account
        $generalAccount = Account::where('is_general', true)->firstOrFail();

        DB::transaction(function () use ($bakeryAccount, $generalAccount, $amount, $date, $description) {
            // Credit bakery's account and update balance
            Transaction::create([
                'account_id' => $bakeryAccount->id,
                'type' => 'credit',
                'amount' => $amount,
                'description' => $description,
                'transaction_date' => $date,
                'reference_number' => $this->generateUniqueReference(),
                'created_by' => Auth::id(),
            ]);
            $bakeryAccount->increment('balance', $amount);

            // Credit general account and update balance
            Transaction::create([
                'account_id' => $generalAccount->id,
                'type' => 'credit',
                'amount' => $amount,
                'description' => $description,
                'transaction_date' => $date,
                'reference_number' => $this->generateUniqueReference(),
                'created_by' => Auth::id(),
            ]);
            $generalAccount->increment('balance', $amount);
        });

        return Redirect::back()->with('success', 'Payment recorded successfully.');
    }

    private function generateUniqueReference(): string
    {
        do {
            $reference = 'PAY-' . strtoupper(uniqid());
        } while (Transaction::where('reference_number', $reference)->exists());

        return $reference;
    }
}
