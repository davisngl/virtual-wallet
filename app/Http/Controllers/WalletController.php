<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWalletRequest;
use App\Http\Requests\UpdateWalletRequest;
use App\Models\Wallet;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class WalletController extends Controller
{
    public function index(): View
    {
        return view('wallet.index', [
            'wallets' => auth()->user()->wallets()->latest()->get()
        ]);
    }

    public function create(): View
    {
        return view('wallet.create');
    }

    public function store(StoreWalletRequest $request): RedirectResponse
    {
        auth()->user()->wallets()->create($request->validated());

        session()->flash('success', 'Wallet successfully created!');

        return redirect(route('wallet.index'));
    }

    public function destroy(Wallet $wallet): RedirectResponse
    {
        $wallet->delete();

        return redirect(route('dashboard'));
    }

    public function edit(Wallet $wallet): View
    {
        return view('wallet.update', ['wallet' => $wallet]);
    }

    public function update(UpdateWalletRequest $request, Wallet $wallet): RedirectResponse
    {
        $wallet->update($request->validated());

        session()->flash('success', 'Wallet successfully updated!');

        return redirect(route('wallet.index'));
    }

    public function statements(Wallet $wallet): View
    {
        return view('wallet.statements', [
            'currency'      => $wallet->currency,
            'incoming'      => $incoming = $wallet->transactions()->onlyIngoing()->get(),
            'outgoing'      => $outgoing = $wallet->transactions()->onlyOutgoing()->get(),
            'totalIncoming' => $incoming->sum('amount'),
            'totalOutgoing' => $outgoing->sum('amount'),
        ]);
    }
}
