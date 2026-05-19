<?php

namespace App\Http\Controllers;

use App\Models\products;
use App\Models\transactions;
use App\Models\transactions_details;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transactions = transactions::with('details.product')
            ->latest()
            ->paginate(5);

        return view('transaction.index', compact('transactions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = products::all();

        return view('transaction.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = products::findOrFail($request->product_id);

        // Cek stok
        if ($request->quantity > $product->stock) {

            return back()->with(
                'error',
                'Stok produk tidak cukup untuk jumlah pembelian ini.'
            );
        }

        // Hitung subtotal
        $subtotal = $product->price * $request->quantity;

        DB::transaction(function () use ($product, $request, $subtotal) {

            // Simpan transaksi utama
            $transaction = transactions::create([
                'transaction_no' => 'TRX-' . time(),
                'date' => now()->toDateString(),
                'customer_name' => 'Customer Umum',
                'total_price' => $subtotal,
                'status' => 'lunas',
            ]);

            // Simpan detail transaksi
            transactions_details::create([
                'transaction_id' => $transaction->id,
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'unit_price' => $product->price,
                'subtotal' => $subtotal,
            ]);

            // Kurangi stok
            $product->update([
                'stock' => $product->stock - $request->quantity
            ]);
        });

        return redirect('/transaction')
            ->with('success', 'Transaksi berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $transaction = transactions::with('details.product')
            ->findOrFail($id);

        return view('transaction.show', compact('transaction'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(transactions $transaction)
    {
        $products = products::all();

        // Ambil detail transaksi pertama
        $detail = $transaction->details()->first();

        return view(
            'transaction.edit',
            compact('transaction', 'detail', 'products')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, transactions $transaction)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $detail = $transaction->details()->first();

        $oldProductId = $detail->product_id;
        $oldQuantity = $detail->quantity;

        $newProductId = $request->product_id;
        $newQuantity = $request->quantity;

        $newProduct = products::findOrFail($newProductId);

        DB::transaction(function () use (
            $transaction,
            $detail,
            $newProduct,
            $oldProductId,
            $oldQuantity,
            $newProductId,
            $newQuantity
        ) {

            // Jika produk sama
            if ($oldProductId == $newProductId) {

                $quantityDiff = $newQuantity - $oldQuantity;

                // Jika quantity bertambah
                if ($quantityDiff > 0) {

                    if ($quantityDiff > $newProduct->stock) {

                        throw new \Exception(
                            'Stok produk tidak cukup untuk penambahan jumlah.'
                        );
                    }

                    $newProduct->update([
                        'stock' => $newProduct->stock - $quantityDiff
                    ]);
                }

                // Jika quantity berkurang
                elseif ($quantityDiff < 0) {

                    $newProduct->update([
                        'stock' => $newProduct->stock + abs($quantityDiff)
                    ]);
                }
            }

            // Jika produk berbeda
            else {

                $oldProduct = products::findOrFail($oldProductId);

                // Kembalikan stok lama
                $oldProduct->update([
                    'stock' => $oldProduct->stock + $oldQuantity
                ]);

                // Cek stok baru
                if ($newQuantity > $newProduct->stock) {

                    throw new \Exception(
                        'Stok produk baru tidak cukup.'
                    );
                }

                // Kurangi stok baru
                $newProduct->update([
                    'stock' => $newProduct->stock - $newQuantity
                ]);
            }

            // Hitung subtotal baru
            $newSubtotal = $newProduct->price * $newQuantity;

            // Update detail transaksi
            $detail->update([
                'product_id' => $newProductId,
                'quantity' => $newQuantity,
                'unit_price' => $newProduct->price,
                'subtotal' => $newSubtotal,
            ]);

            // Update transaksi utama
            $transaction->update([
                'total_price' => $newSubtotal,
            ]);
        });

        return redirect('/transaction')
            ->with('success', 'Transaksi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(transactions $transaction)
    {
        DB::transaction(function () use ($transaction) {

            // Kembalikan stok produk
            foreach ($transaction->details as $detail) {

                $product = products::findOrFail($detail->product_id);

                $product->update([
                    'stock' => $product->stock + $detail->quantity
                ]);
            }

            // Hapus detail transaksi
            $transaction->details()->delete();

            // Hapus transaksi
            $transaction->delete();
        });

        return redirect('/transaction')
            ->with(
                'success',
                'Transaksi berhasil dihapus.'
            );
    }
}