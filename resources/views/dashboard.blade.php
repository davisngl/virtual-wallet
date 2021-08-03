<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <div class="action-list flex flex-row justify-between">
                        <div class="w-1/4 py-2 px-5 border-green-800 bg-green-200">
                            <p class="text-2xl font-bold pb-5">Wallets</p>
                            <ul class="actions list-disc px-5">
                                <li><a href="{{ route('wallet.create') }}" class="hover:underline">Create a wallet</a></li>
                                <li><a href="{{ route('wallet.index') }}" class="hover:underline">Show all wallets</a></li>
                            </ul>
                        </div>

                        <div class="w-1/4 py-2 px-5 border-blue-800 bg-blue-200">
                            <p class="text-2xl font-bold pb-5">Transactions</p>
                            <ul class="actions list-disc px-5">
                                @forelse($wallets as $wallet)
                                    <li>
                                        <a href="{{ route('transaction.index', ['wallet' => $wallet->id]) }}" class="hover:underline">
                                            "{{ strtoupper($wallet->currency) }}" wallet transaction list
                                        </a>
                                    </li>
                                @empty
                                    No wallets created to have any transactions. Create one wallet first.
                                @endforelse
                            </ul>
                        </div>

                        <div class="w-1/4 py-2 px-5 border-yellow-800 bg-yellow-200">
                            <p class="text-2xl font-bold pb-5">Payments & wallet status</p>
                            <ul class="actions list-disc px-5">
                                <li><a href="#" class="hover:underline">Make a transaction</a></li>
                                <li><a href="#" class="hover:underline">Balance view</a></li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
