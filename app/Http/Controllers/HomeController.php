<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        return view('dashboard', [
            'wallets' => auth()->user()->wallets()->latest()->get(),
        ]);
    }
}
