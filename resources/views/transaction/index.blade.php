<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Transactions for wallet ID: {{ $wallet->id }}
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
                        @forelse($transactions as $transaction)
                                <div class="transaction-info flex flex-row justify-between w-full py-2 px-5 mb-5 {{ $transaction->fraudulent() ? 'bg-red-200' : 'bg-green-200' }}">
                                <div class="transaction-info">
                                    <div class="w-full flex flex-row space-x-5">
                                        <span>Amount: {{ $transaction->amount }}</span>
                                        <span>Currency: {{ strtoupper($transaction->currency) }}</span>
                                        <span>Type: {{ ucfirst($transaction->type) }}</span>
                                        <span>Verdict: {{ ucfirst($transaction->verdict) }}</span>
                                    </div>
                                </div>

                                <div class="wallet-actions">
                                    <form action="{{ route('transaction.destroy', ['transaction' => $transaction]) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="hover:underline">Delete</button>
                                    </form>

                                    @if ($transaction->ok())
                                        <form action="{{ route('transaction.verdict', ['transaction' => $transaction]) }}" method="post">
                                            @csrf
                                            @method('PATCH')

                                            <input name="verdict" type="hidden" value="fraudulent">

                                            <button type="submit" class="hover:underline">Mark as fraudulent</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p>No transactions have been made. <a href="{{ route('transaction.create', ['wallet' => $wallet->id]) }}" class="text-blue-800">Make one?</a></p>
                        @endforelse

                    </div>

                    <a href="{{ route('transaction.create', ['wallet' => $wallet->id]) }}" class="py-2 px-5 bg-green-500 text-white uppercase rounded-md">Create a Transaction</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
