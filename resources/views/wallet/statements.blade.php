<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Wallet Statement') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <div class="statements flex mb-5 space-x-5 pb-5 border-b-2 border-gray-400">
                        <div class="ingoing w-1/2 mb-2">
                            <h1 class="text-2xl mb-2">In-coming</h1>
                            @forelse($incoming as $incomingPayment)
                                <div class="payment py-2 px-4 mb-2 flex justify-between bg-green-500 text-white rounded">
                                    <span>Amount: {{ $incomingPayment->amount }}</span>
                                    <span>Currency: {{ $incomingPayment->currency }}</span>
                                    <span>Verdict: {{ ucfirst($incomingPayment->verdict) }}</span>
                                    <span>Date: {{ $incomingPayment->created_at->format('H:i d.m.Y') }}</span>
                                </div>
                            @empty
                                No in-coming transactions.
                            @endforelse
                        </div>

                        <div class="outgoing w-1/2 mb-2">
                            <h1 class="text-2xl mb-2">Out-going</h1>

                            @forelse($outgoing as $outgoingPayment)
                                <div class="payment py-2 px-4 mb-2 flex justify-between bg-yellow-500 text-white rounded">
                                    <span>Amount: {{ $outgoingPayment->amount }}</span>
                                    <span>Currency: {{ $outgoingPayment->currency }}</span>
                                    <span>Verdict: {{ ucfirst($outgoingPayment->verdict) }}</span>
                                    <span>Date: {{ $outgoingPayment->created_at->format('H:i d.m.Y') }}</span>
                                </div>
                            @empty
                                No out-going transactions.
                            @endforelse
                        </div>
                    </div>
                    <div class="summary flex space-x-5">
                        <div class="incoming w-1/2">
                            Total in-coming: {{ $totalIncoming }} {{ strtoupper($currency) }}
                        </div>

                        <div class="outgoing w-1/2">
                            Total out-going: {{ $totalOutgoing }} {{ strtoupper($currency) }}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
