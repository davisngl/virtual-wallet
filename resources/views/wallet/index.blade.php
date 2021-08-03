<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Wallets') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    @if(session('success'))
                        <div class="py-2 px-5 bg-green-500 text-white rounded mb-5">
                            {{ session()->get('success') }}
                        </div>
                    @endif

                    <div class="wallets flex flex-col mb-5">
                        @forelse($wallets as $wallet)
                            <div class="wallet-info flex flex-row justify-between w-full py-2 px-5 mb-5 bg-green-200">
                                <div class="wallet-info">
                                    <h1 class="text-2xl mb-5">{{ $wallet->name }}</h1>
                                    <div class="w-full flex flex-row space-x-5">
                                        <span>Amount: {{ $wallet->amount }}</span>
                                        <span>Currency: {{ strtoupper($wallet->currency) }}</span>
                                    </div>
                                </div>

                                <div class="wallet-actions">
                                    <ul class="list-disc">
                                        <li><a href="{{ route('transaction.index', ['wallet' => $wallet->id]) }}" class="hover:underline">View transactions</a></li>
                                    </ul>
                                </div>
                            </div>
                        @empty
                            <p>No wallets have been created. <a href="{{ route('wallet.create') }}" class="text-blue-800">Create one?</a></p>
                        @endforelse

                    </div>

                    <a href="{{ route('wallet.create') }}" class="py-2 px-5 bg-green-500 text-white uppercase rounded-md">Create a Wallet</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
