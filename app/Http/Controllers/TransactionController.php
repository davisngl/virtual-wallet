<?php

namespace App\Http\Controllers;

use App\Http\Requests\MarkTransactionRequest;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TransactionController extends Controller
{
    public function index(Wallet $wallet): View
    {
        return view('transaction.index', [
            'wallet'       => $wallet,
            'transactions' => $wallet->transactions
        ]);
    }

    public function destroy(Transaction $transaction): RedirectResponse
    {
        $transaction->delete();

        session()->flash('success', 'Transaction successfully deleted!');

        return redirect(route('transaction.index', ['wallet' => $transaction->wallet_id]));
    }

    public function mark(MarkTransactionRequest $request, Transaction $transaction): RedirectResponse
    {
        $transaction->markAsFraudulent();

        return redirect(route('transaction.index', ['wallet' => $transaction->wallet_id]));
    }
}
