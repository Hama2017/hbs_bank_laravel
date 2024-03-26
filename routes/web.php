<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\TellerDashboardController;
use App\Http\Controllers\CustomerDashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/redirects','App\Http\Controllers\HomeController@index');


Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard','App\Http\Controllers\HomeController@index')->name('dashboard');

    Route::get('/customer/dashboard', [CustomerDashboardController::class, 'index'])->name('customer.dashboard');

    Route::get('/current-account', [CustomerDashboardController::class, 'currentAccount'])->name('current_account');
    Route::get('/savingsAccount', [CustomerDashboardController::class, 'savingsAccount'])->name('savingsAccount');

});


Route::middleware(['teller'])->group(function () {
    // Routes accessibles uniquement aux guichetiers

    Route::get('/teller/dashboard', [TellerDashboardController::class, 'index'])->name('teller.dashboard');
    Route::post('/teller/deposit', [TellerDashboardController::class, 'deposit'])->name('teller.deposit');
    Route::post('/teller/withdraw', [TellerDashboardController::class, 'withdraw'])->name('teller.withdraw');

});



Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/users', [AdminDashboardController::class, 'users'])->name('admin.users');
    Route::get('/admin/accounts', [AdminDashboardController::class, 'accounts'])->name('admin.accounts');
    Route::get('/admin/transactions', [AdminDashboardController::class, 'transactions'])->name('admin.transactions');
    Route::put('/admin/accounts/toggle-status', [AdminDashboardController::class, 'toggleAccountStatus'])->name('admin.accounts.toggle-status');
});


Route::get('/account-types', [ApiController::class, 'getAccountTypes']);
Route::get('/packages', [ApiController::class, 'getPackages']);
Route::post('/api/openCurrentAccount',[ApiController::class, 'openCurrentAccount']);
Route::post('/api/openSavingsAccount', [ApiController::class, 'openSavingsAccount']);
Route::get('/api/currentAccountBalance', [ApiController::class, 'currentAccountBalance']);
Route::get('/api/currentAccountTransactions', [ApiController::class, 'currentAccountTransactions']);
Route::get('/api/savingsAccountBalance', [ApiController::class, 'savingsAccountBalance']);
Route::get('/api/savingsAccountTransactions', [ApiController::class, 'savingsAccountTransactions']);
Route::post('/api/createCreditCard', [ApiController::class, 'createCreditCard']);
Route::get('/api/getVirtualCards', [ApiController::class, 'getVirtualCards']);
Route::post('/api/addBeneficiary', [ApiController::class, 'addBeneficiary']);
Route::post('/api/transfer', [ApiController::class, 'transfer']);
Route::get('/api/beneficiaries', [ApiController::class, 'getBeneficiaries']);


