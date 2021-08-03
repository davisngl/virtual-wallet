<?php

use App\Http\Controllers\TransactionController;
use App\Http\Controllers\WalletController;
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

Route::middleware(['auth'])->group(function () {
    Route::view('/', 'dashboard')->name('dashboard');

    // Wallets
    Route::get('wallets', [WalletController::class, 'index'])->name('wallet.index');
    Route::get('wallets/create', [WalletController::class, 'create'])->name('wallet.create');
    Route::post('wallets', [WalletController::class, 'store'])->name('wallet.store');
    Route::delete('wallets/{wallet}', [WalletController::class, 'destroy'])->name('wallet.destroy');

    // Transactions
    Route::get('wallets/{wallet}/transactions', [TransactionController::class, 'index'])->name('transaction.index');
    Route::get('wallets/{wallet}/transactions/create', [TransactionController::class, 'create'])->name('transaction.create');
    Route::post('wallets/{wallet}/transactions', [TransactionController::class, 'store'])->name('transaction.store');
    Route::delete('wallets/{wallet}/transactions/{transaction}', [TransactionController::class, 'destroy'])->name('transaction.destroy');
});

require __DIR__ . '/auth.php';
