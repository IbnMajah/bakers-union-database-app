<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ContactsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImagesController;
use App\Http\Controllers\OrganizationsController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\BakeriesController;
use App\Http\Controllers\AccountsController;
use App\Http\Controllers\TransactionsController;
use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\ExpenseCategoriesController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Auth

Route::get('login', [AuthenticatedSessionController::class, 'create'])
    ->name('login')
    ->middleware('guest');

Route::post('login', [AuthenticatedSessionController::class, 'store'])
    ->name('login.store')
    ->middleware('guest');

Route::delete('logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

// Dashboard

Route::get('/', [DashboardController::class, 'index'])
    ->name('dashboard')
    ->middleware('auth');

// Users

Route::get('users', [UsersController::class, 'index'])
    ->name('users')
    ->middleware('auth');

Route::get('users/create', [UsersController::class, 'create'])
    ->name('users.create')
    ->middleware('auth');

Route::post('users', [UsersController::class, 'store'])
    ->name('users.store')
    ->middleware('auth');

Route::get('users/{user}/edit', [UsersController::class, 'edit'])
    ->name('users.edit')
    ->middleware('auth');

Route::put('users/{user}', [UsersController::class, 'update'])
    ->name('users.update')
    ->middleware('auth');

Route::delete('users/{user}', [UsersController::class, 'destroy'])
    ->name('users.destroy')
    ->middleware('auth');

Route::put('users/{user}/restore', [UsersController::class, 'restore'])
    ->name('users.restore')
    ->middleware('auth');

// Organizations

Route::get('organizations', [OrganizationsController::class, 'index'])
    ->name('organizations')
    ->middleware('auth');

Route::get('organizations/create', [OrganizationsController::class, 'create'])
    ->name('organizations.create')
    ->middleware('auth');

Route::post('organizations', [OrganizationsController::class, 'store'])
    ->name('organizations.store')
    ->middleware('auth');

Route::get('organizations/{organization}/edit', [OrganizationsController::class, 'edit'])
    ->name('organizations.edit')
    ->middleware('auth');

Route::put('organizations/{organization}', [OrganizationsController::class, 'update'])
    ->name('organizations.update')
    ->middleware('auth');

Route::delete('organizations/{organization}', [OrganizationsController::class, 'destroy'])
    ->name('organizations.destroy')
    ->middleware('auth');

Route::put('organizations/{organization}/restore', [OrganizationsController::class, 'restore'])
    ->name('organizations.restore')
    ->middleware('auth');

// Contacts

Route::get('contacts', [ContactsController::class, 'index'])
    ->name('contacts')
    ->middleware('auth');

Route::get('contacts/create', [ContactsController::class, 'create'])
    ->name('contacts.create')
    ->middleware('auth');

Route::post('contacts', [ContactsController::class, 'store'])
    ->name('contacts.store')
    ->middleware('auth');

Route::get('contacts/{contact}/edit', [ContactsController::class, 'edit'])
    ->name('contacts.edit')
    ->middleware('auth');

Route::put('contacts/{contact}', [ContactsController::class, 'update'])
    ->name('contacts.update')
    ->middleware('auth');

Route::delete('contacts/{contact}', [ContactsController::class, 'destroy'])
    ->name('contacts.destroy')
    ->middleware('auth');

Route::put('contacts/{contact}/restore', [ContactsController::class, 'restore'])
    ->name('contacts.restore')
    ->middleware('auth');

// Reports

Route::get('reports', [ReportsController::class, 'index'])
    ->name('reports')
    ->middleware('auth');

Route::get('reports/generate/{type}/{summaryType?}/{format?}', [ReportsController::class, 'generate'])
    ->name('reports.generate')
    ->middleware('auth')
    ->where('type', 'transactions|expenses|summary')
    ->where('summaryType', 'weekly|monthly|yearly')
    ->where('format', 'pdf|xlsx');

// Images

Route::get('/img/{path}', [ImagesController::class, 'show'])
    ->where('path', '.*')
    ->name('image');

// Bakeries
Route::get('bakeries', [BakeriesController::class, 'index'])
    ->name('bakeries')
    ->middleware('auth');

Route::get('bakeries/create', [BakeriesController::class, 'create'])
    ->name('bakeries.create')
    ->middleware('auth');

Route::post('bakeries', [BakeriesController::class, 'store'])
    ->name('bakeries.store')
    ->middleware('auth');

Route::get('bakeries/{bakery}/edit', [BakeriesController::class, 'edit'])
    ->name('bakeries.edit')
    ->middleware('auth');

Route::put('bakeries/{bakery}', [BakeriesController::class, 'update'])
    ->name('bakeries.update')
    ->middleware('auth');

Route::delete('bakeries/{bakery}', [BakeriesController::class, 'destroy'])
    ->name('bakeries.destroy')
    ->middleware('auth');

Route::put('bakeries/{bakery}/restore', [BakeriesController::class, 'restore'])
    ->name('bakeries.restore')
    ->middleware('auth');

Route::post('bakeries/{bakery}/payment', [BakeriesController::class, 'recordPayment'])
    ->name('bakeries.payment')
    ->middleware('auth');

// Accounts
Route::get('accounts', [AccountsController::class, 'index'])
    ->name('accounts')
    ->middleware('auth');

Route::get('accounts/create', [AccountsController::class, 'create'])
    ->name('accounts.create')
    ->middleware('auth');

Route::post('accounts', [AccountsController::class, 'store'])
    ->name('accounts.store')
    ->middleware('auth');

Route::get('accounts/{account}/edit', [AccountsController::class, 'edit'])
    ->name('accounts.edit')
    ->middleware('auth');

Route::put('accounts/{account}', [AccountsController::class, 'update'])
    ->name('accounts.update')
    ->middleware('auth');

Route::delete('accounts/{account}', [AccountsController::class, 'destroy'])
    ->name('accounts.destroy')
    ->middleware('auth');

Route::put('accounts/{account}/restore', [AccountsController::class, 'restore'])
    ->name('accounts.restore')
    ->middleware('auth');

// Transactions
Route::get('transactions', [TransactionsController::class, 'index'])
    ->name('transactions')
    ->middleware('auth');

Route::get('transactions/create', [TransactionsController::class, 'create'])
    ->name('transactions.create')
    ->middleware('auth');

Route::post('transactions', [TransactionsController::class, 'store'])
    ->name('transactions.store')
    ->middleware('auth');

Route::get('transactions/{transaction}/edit', [TransactionsController::class, 'edit'])
    ->name('transactions.edit')
    ->middleware('auth');

Route::put('transactions/{transaction}', [TransactionsController::class, 'update'])
    ->name('transactions.update')
    ->middleware('auth');

Route::delete('transactions/{transaction}', [TransactionsController::class, 'destroy'])
    ->name('transactions.destroy')
    ->middleware('auth');

Route::put('transactions/{transaction}/restore', [TransactionsController::class, 'restore'])
    ->name('transactions.restore')
    ->middleware('auth');

// Expenses
Route::get('expenses', [ExpensesController::class, 'index'])
    ->name('expenses')
    ->middleware('auth');

Route::get('expenses/create', [ExpensesController::class, 'create'])
    ->name('expenses.create')
    ->middleware('auth');

Route::post('expenses', [ExpensesController::class, 'store'])
    ->name('expenses.store')
    ->middleware('auth');

Route::get('expenses/{expense}/edit', [ExpensesController::class, 'edit'])
    ->name('expenses.edit')
    ->middleware('auth');

Route::put('expenses/{expense}', [ExpensesController::class, 'update'])
    ->name('expenses.update')
    ->middleware('auth');

Route::delete('expenses/{expense}', [ExpensesController::class, 'destroy'])
    ->name('expenses.destroy')
    ->middleware('auth');

Route::put('expenses/{expense}/restore', [ExpensesController::class, 'restore'])
    ->name('expenses.restore')
    ->middleware('auth');

Route::put('expenses/{expense}/approve', [ExpensesController::class, 'approve'])
    ->name('expenses.approve')
    ->middleware('auth');

Route::put('expenses/{expense}/reject', [ExpensesController::class, 'reject'])
    ->name('expenses.reject')
    ->middleware('auth');

// Expense Categories
Route::get('expense-categories', [ExpenseCategoriesController::class, 'index'])
    ->name('expense-categories')
    ->middleware('auth');

Route::get('expense-categories/create', [ExpenseCategoriesController::class, 'create'])
    ->name('expense-categories.create')
    ->middleware('auth');

Route::post('expense-categories', [ExpenseCategoriesController::class, 'store'])
    ->name('expense-categories.store')
    ->middleware('auth');

Route::get('expense-categories/{expenseCategory}/edit', [ExpenseCategoriesController::class, 'edit'])
    ->name('expense-categories.edit')
    ->middleware('auth');

Route::put('expense-categories/{expenseCategory}', [ExpenseCategoriesController::class, 'update'])
    ->name('expense-categories.update')
    ->middleware('auth');

Route::delete('expense-categories/{expenseCategory}', [ExpenseCategoriesController::class, 'destroy'])
    ->name('expense-categories.destroy')
    ->middleware('auth');

Route::put('expense-categories/{expenseCategory}/restore', [ExpenseCategoriesController::class, 'restore'])
    ->name('expense-categories.restore')
    ->middleware('auth');
