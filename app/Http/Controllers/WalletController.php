<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWalletRequest;
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
}
