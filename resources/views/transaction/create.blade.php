<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Transaksi') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{-- Tombol Kembali --}}
                    <a href="{{ route('transaction.index') }}"
                       class="text-blue-500 hover:text-blue-700">
                        ← Kembali
                    </a>

                    @if($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mt-4 mb-4">
                            <ul class="list-disc ml-5">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('transaction.store') }}"
                          method="POST"
                          class="mt-6">

                        @csrf

                        <div class="mb-4">

                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Pilih Produk
                            </label>

                            <select
                                name="product_id"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">
                                    -- Pilih Produk --
                                </option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">
                                        {{ $product->name }}
                                        | Stock: {{ $product->stock }}
                                        | Rp {{ number_format($product->price,0,',','.') }}
                                    </option>
                                @endforeach
                            </select>

                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Quantity
                            </label>
                            <input
                                type="number"
                                name="quantity"
                                min="1"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="Masukkan jumlah pembelian"
                            >
                        </div>

                        <div class="flex items-center gap-3">
                            <button
                                type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>