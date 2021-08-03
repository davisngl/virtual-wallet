<?php

namespace App\Http\Controllers;

use App\Events\TransactionDeleted;
use App\Http\Requests\MarkTransactionRequest;
use App\Http\Requests\StoreTransactionRequest;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TransactionController extends Controller
{
    public function index(Wallet $wallet): View
    {
        return view('transaction.index', [
            'wallet' => $wallet,
        ]);
    }

    public function create(Wallet $wallet)
    {
        return view('transaction.create', ['wallet' => $wallet->id]);
    }

    public function store(StoreTransactionRequest $request, Wallet $wallet): RedirectResponse
    {
        $wallet->{$request->get('type')}($request->get('amount'));

        return redirect(route('transaction.index', ['wallet' => $wallet->id]));
    }

    public function destroy(Transaction $transaction): RedirectResponse
    {
        $transaction->delete();

        event(new TransactionDeleted($transaction->wallet));
        session()->flash('success', 'Transaction successfully deleted!');

        return redirect(route('transaction.index', ['wallet' => $transaction->wallet_id]));
    }

    public function mark(MarkTransactionRequest $request, Transaction $transaction): RedirectResponse
    {
        $transaction->markAsFraudulent();

        return redirect(route('transaction.index', ['wallet' => $transaction->wallet_id]));
    }
}
