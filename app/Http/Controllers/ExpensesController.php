<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Bakery;
use App\Models\Account;
use App\Models\Expense;
use App\Models\Transaction;
use App\Models\ExpenseCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;

class ExpensesController extends Controller
{
    public function index()
    {
        return Inertia::render('Expenses/Index', [
            'filters' => Request::all('search', 'trashed'),
            'expenses' => Expense::query()
                ->with(['category', 'bakery', 'creator', 'approver'])
                ->orderByDesc('expense_date')
                ->filter(Request::only('search', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($expense) => [
                    'id' => $expense->id,
                    'bakery' => $expense->bakery->name,
                    'category' => $expense->category->name,
                    'amount' => $expense->amount,
                    'description' => $expense->description,
                    'receipt_number' => $expense->receipt_number,
                    'expense_date' => $expense->expense_date->format('Y-m-d'),
                    'status' => $expense->status,
                    'creator' => $expense->creator->full_name,
                    'approver' => $expense->approver ? $expense->approver->full_name : null,
                    'approved_at' => $expense->approved_at ? $expense->approved_at->format('Y-m-d') : null,
                    'deleted_at' => $expense->deleted_at
                ]),
        ]);
    }

    public function create()
    {
        return Inertia::render('Expenses/Create', [
            'bakeries' => Bakery::orderBy('name')->get(['id', 'name']),
            'categories' => ExpenseCategory::orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function store()
    {
        Request::validate([
            'bakery_id' => ['required', 'exists:bakeries,id'],
            'expense_category_id' => ['required', 'exists:expense_categories,id'],
            'amount' => ['required', 'numeric', 'min:0'],
            'description' => ['required', 'max:255'],
            'receipt_number' => ['nullable', 'max:50'],
            'expense_date' => ['required', 'date'],
        ]);

        DB::transaction(function () {
            // Generate receipt number if not provided
            $receiptNumber = Request::get('receipt_number') ?: $this->generateReceiptNumber();

            $expense = Expense::create([
                'bakery_id' => Request::get('bakery_id'),
                'expense_category_id' => Request::get('expense_category_id'),
                'amount' => Request::get('amount'),
                'description' => Request::get('description'),
                'receipt_number' => $receiptNumber,
                'expense_date' => Request::get('expense_date'),
                'status' => 'pending',
                'created_by' => Auth::id(),
            ]);

            $amount = Request::get('amount');
            $bakery = Bakery::findOrFail(Request::get('bakery_id'));
            $generalAccount = Account::where('is_general', true)->first();

            $bakeryAccount = Account::where('bakery_id', $bakery->id)->first();

            Log::info('Bakery: '. $bakery);
            Log::info('Account id: '. $bakeryAccount);
            // Create transaction records for both accounts
            Transaction::create([
                'account_id' => $bakeryAccount->id,
                'amount' => -$amount,  // negative amount for debit
                'description' => 'Expense: ' . $expense->description,
                'transaction_date' => Request::get('expense_date'),
                'created_by' => Auth::id(),
                'type' => 'debit',
                'reference_number' => $this->generateUniqueReference(),
            ]);

            Transaction::create([
                'account_id' => $generalAccount->id,
                'amount' => -$amount,  // negative amount for debit
                'description' => 'Expense: ' . $expense->description,
                'transaction_date' => Request::get('expense_date'),
                'created_by' => Auth::id(),
                'type' => 'credit',
                'reference_number' => $this->generateUniqueReference(),
            ]);

            // Debit both accounts
            $bakery->account->decrement('balance', $amount);
            $generalAccount->decrement('balance', $amount);
        });

        return Redirect::route('expenses')->with('success', 'Expense created.');
    }

    public function edit(Expense $expense)
    {
        return Inertia::render('Expenses/Edit', [
            'expense' => [
                'id' => $expense->id,
                'bakery_id' => $expense->bakery_id,
                'expense_category_id' => $expense->expense_category_id,
                'amount' => $expense->amount,
                'description' => $expense->description,
                'receipt_number' => $expense->receipt_number,
                'expense_date' => $expense->expense_date->format('Y-m-d'),
                'status' => $expense->status,
                'deleted_at' => $expense->deleted_at,
            ],
            'bakeries' => Bakery::orderBy('name')->get(['id', 'name']),
            'categories' => ExpenseCategory::orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function update(Expense $expense)
    {
        Request::validate([
            'amount' => ['required', 'numeric', 'min:0'],
            'description' => ['required', 'max:255'],
            'date' => ['required', 'date'],
        ]);

        DB::transaction(function () use ($expense) {
            // Get the old amount to calculate the difference
            $oldAmount = $expense->amount;
            $newAmount = Request::get('amount');
            $amountDifference = $newAmount - $oldAmount;

            // Update the expense record
            $expense->update([
                'amount' => $newAmount,
                'description' => Request::get('description'),
                'expense_date' => Request::get('date'),
            ]);

            // Only update account balances if amount has changed
            if ($amountDifference != 0) {
                $bakeryAccount = Account::where('bakery_id', $expense->bakery_id)->first();
                $generalAccount = Account::where('is_general', true)->first();

                // Update account balances
                $bakeryAccount->increment('balance', -$amountDifference);
                $generalAccount->increment('balance', -$amountDifference);

                // Create new transaction records for the difference
                if ($amountDifference != 0) {

                    Transaction::create([
                        'account_id' => $bakeryAccount->id,
                        'amount' => -$amountDifference,
                        'description' => 'Expense adjustment: ' . $expense->description,
                        'transaction_date' => Request::get('date'),
                        'created_by' => Auth::id(),
                        'type' => 'debit',
                        'reference_number' => $this->generateUniqueReference(),
                    ]);

                    Transaction::create([
                        'account_id' => $generalAccount->id,
                        'amount' => -$amountDifference,
                        'description' => 'Expense adjustment: ' . $expense->description,
                        'transaction_date' => Request::get('date'),
                        'created_by' => Auth::id(),
                        'type' => 'credit',
                        'reference_number' => $this->generateUniqueReference(),
                    ]);
                }
            }
        });

        return Redirect::route('expenses')->with('success', 'Expense updated.');
    }

    public function approve(Expense $expense)
    {
        if ($expense->status !== 'pending') {
            return Redirect::route('expenses')->with('error', 'Expense is not pending approval.');
        }

        $expense->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return Redirect::route('expenses')->with('success', 'Expense approved.');
    }

    public function reject(Expense $expense)
    {
        if ($expense->status !== 'pending') {
            return Redirect::route('expenses')->with('error', 'Expense is not pending approval.');
        }

        $expense->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return Redirect::route('expenses')->with('success', 'Expense rejected.');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return Redirect::route('expenses')->with('success', 'Expense deleted.');
    }

    public function restore(Expense $expense)
    {
        $expense->restore();
        return Redirect::route('expenses')->with('success', 'Expense restored.');
    }

    private function generateUniqueReference(): string
    {
        do {
            $reference = 'PAY-' . strtoupper(uniqid());
        } while (Transaction::where('reference_number', $reference)->exists());

        return $reference;
    }

    private function generateReceiptNumber(): string
    {
        do {
            $receipt = 'EXP-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));
        } while (Expense::where('receipt_number', $receipt)->exists());

        return $receipt;
    }
}