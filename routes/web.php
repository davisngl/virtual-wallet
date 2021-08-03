<?php

use App\Http\Controllers\HomeController;
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
    Route::get('/', HomeController::class)->name('dashboard');

    // Wallets
    Route::prefix('wallets')->group(function () {
        Route::get('/', [WalletController::class, 'index'])->name('wallet.index');
        Route::get('create', [WalletController::class, 'create'])->name('wallet.create');
        Route::post('/', [WalletController::class, 'store'])->name('wallet.store');
        Route::get('{wallet}', [WalletController::class, 'edit'])->name('wallet.edit');
        Route::post('{wallet}', [WalletController::class, 'update'])->name('wallet.update');
        Route::delete('{wallet}', [WalletController::class, 'destroy'])->name('wallet.destroy');
    });

    // Statements (incoming & outgoing transaction data related pages)
    Route::get('wallets/{wallet}/statements', [WalletController::class, 'statements'])->name('wallet.statements');

    // Transactions
    Route::get('wallets/{wallet}/transactions', [TransactionController::class, 'index'])->name('transaction.index');
    Route::get('wallets/{wallet}/transactions/create', [TransactionController::class, 'create'])->name('transaction.create');
    Route::post('wallets/{wallet}/transactions', [TransactionController::class, 'store'])->name('transaction.store');
    Route::patch('transactions/{transaction}/mark', [TransactionController::class, 'mark'])->name('transaction.verdict');
    Route::delete('transactions/{transaction}', [TransactionController::class, 'destroy'])->name('transaction.destroy');
});

require __DIR__ . '/auth.php';
