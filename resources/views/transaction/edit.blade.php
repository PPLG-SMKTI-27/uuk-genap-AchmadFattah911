<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-gray-800">
                Edit Transaksi
            </h2>

            <a href="{{ route('transaction.index') }}"
               class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                ← Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">

            {{-- Card --}}
            <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
                {{-- Header Card --}}
                <div class="px-8 py-6 border-b border-gray-100 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-800">
                        Form Edit Transaksi
                    </h3>
                    <p class="text-sm text-gray-500 mt-1">
                        Ubah produk dan quantity transaksi.
                    </p>
                </div>

                {{-- Content --}}
                <div class="p-8">
                    {{-- Error --}}
                    @if($errors->any())
                        <div class="mb-6 rounded-xl bg-red-50 border border-red-200 p-4">
                            <ul class="list-disc ml-5 text-sm text-red-600 space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Form --}}
                    <form action="{{ route('transaction.update', $transaction->id) }}"
                          method="POST"
                          class="space-y-6">
                        @csrf
                        @method('PUT')

                        {{-- Product --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Pilih Produk
                            </label>

                            <select
                                name="product_id"
                                class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">

                                @foreach($products as $product)

                                    <option
                                        value="{{ $product->id }}"
                                        {{ $detail->product_id == $product->id ? 'selected' : '' }}>

                                        {{ $product->product_name }}
                                        | Stock: {{ $product->stock }}
                                        | Rp {{ number_format($product->price,0,',','.') }}
                                    </option>
                                @endforeach

                            </select>
                        </div>

                        {{-- Quantity --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Quantity
                            </label>

                            <input
                                type="number"
                                name="quantity"
                                min="1"
                                value="{{ $detail->quantity }}"
                                placeholder="Masukkan quantity"
                                class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                        </div>

                        {{-- Button --}}
                        <div class="pt-4 flex items-center gap-3">
                            <button
                                type="submit"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-3 rounded-xl transition duration-200 shadow-sm">
                                Update Transaksi
                            </button>
                            <a href="{{ route('transaction.index') }}"
                               class="px-6 py-3 rounded-xl border border-gray-300 text-gray-700 hover:bg-gray-100 transition duration-200">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>