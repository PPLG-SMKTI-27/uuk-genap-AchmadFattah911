<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Transactions') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Tombol Tambah Transaksi --}}
                    <a href="{{ route('transaction.create') }}"
                       class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Tambah Transaksi
                    </a>



                    {{-- Search --}}
                    <div class="mt-6 mb-4">

                        <form method="GET"
                              action="{{ route('transaction.index') }}"
                              class="flex">

                            <input
                                type="text"
                                name="search"
                                placeholder="Cari customer atau status..."
                                value="{{ $search ?? '' }}"
                                class="border-gray-300 rounded-l-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 flex-1"
                            >

                            <button
                                type="submit"
                                class="bg-gray-800 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-r-md">

                                Search

                            </button>

                        </form>

                    </div>



                    {{-- Alert Success --}}
                    @if(session('success'))

                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">

                            {{ session('success') }}

                        </div>

                    @endif



                    {{-- Alert Error --}}
                    @if(session('error'))

                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">

                            {{ session('error') }}

                        </div>

                    @endif



                    {{-- Table --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            {{-- Header --}}
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        No Transaksi
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tanggal
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Customer
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Total Harga
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>



                            {{-- Body --}}
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($transactions as $transaction)
                                    <tr>
                                        {{-- Nomor Transaksi --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $transaction->transaction_no ?? '-' }}
                                        </td>

                                        {{-- Tanggal --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $transaction->date }}
                                        </td>

                                        {{-- Customer --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $transaction->customer_name }}
                                        </td>

                                        {{-- Total Harga --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            Rp {{ number_format($transaction->total_price, 0, ',', '.') }}
                                        </td>

                                        {{-- Status --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ ucfirst($transaction->status) }}
                                        </td>

                                        {{-- Aksi --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">

                                            {{-- Edit --}}
                                            <a href="{{ route('transaction.edit', $transaction->id) }}"
                                               class="text-indigo-600 hover:text-indigo-900 mr-3">
                                                Edit
                                            </a>

                                            {{-- Delete --}}
                                            <form action="{{ route('transaction.destroy', $transaction->id) }}"
                                                  method="POST"
                                                  class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    type="submit"
                                                    class="text-red-600 hover:text-red-900"
                                                    onclick="return confirm('Yakin hapus transaksi ini?')">
                                                    Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>


                                @empty
                                    <tr>
                                        <td colspan="6"
                                            class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                            Data transaksi belum ada.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-4">
                        {{ $transactions->appends([
                            'search' => $search ?? ''
                        ])->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>