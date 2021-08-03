<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create a Transaction') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <form action="{{ route('transaction.store', ['wallet' => $wallet]) }}" method="post">
                        @csrf

                        <div class="w-3/4 flex flex-row justify-between mb-5 space-x-2">
                            <div class="col-span-6 sm:col-span-3 w-1/2">
                                <label for="amount" class="block text-sm font-medium text-gray-700">Amount</label>
                                <input type="number"
                                       min="0"
                                       name="amount"
                                       id="amount"
                                       class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                       value="{{ old('amount') }}"
                                >
                                @error('amount')<p class="text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div class="col-span-6 sm:col-span-4 w-1/2">
                                <label for="type" class="block text-sm font-medium text-gray-700">Type</label>
                                <select name="type" id="type">
                                    <option value="deposit" {{ old('type') === 'deposit' ? 'selected' : '' }}>Deposit</option>
                                    <option value="withdraw" {{ old('type') === 'withdraw' ? 'selected' : '' }}>Withdraw</option>
                                </select>
                                @error('currency')<p class="text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <button type="submit" class="py-2 px-5 bg-green-500 text-white uppercase rounded-md">Create a Transaction</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
