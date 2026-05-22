<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\products;
use App\Models\categories;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->search;

        $products = products::with('category')
            ->when($search, function ($query) use ($search) {

                $query->where('product_name', 'like', '%' . $search . '%')

                    ->orWhereHas('category', function ($q) use ($search) {

                        $q->where('category_name', 'like', '%' . $search . '%');
                    });
            })
            ->latest()
            ->paginate(5);

        return view('product.index', compact('products', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = categories::all();

        return view('product.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'product_name' => 'required|max:255',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'unit' => 'required|max:50',
        ]);

        products::create([
            'category_id' => $request->category_id,
            'product_name' => $request->product_name,
            'price' => $request->price,
            'stock' => $request->stock,
            'unit' => $request->unit,
        ]);

        return redirect('/product')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = products::with('category')->findOrFail($id);

        return view('product.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = products::findOrFail($id);

        $categories = categories::all();

        return view('product.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'product_name' => 'required|max:255',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'unit' => 'required|max:50',
        ]);

        $product = products::findOrFail($id);

        $product->update([
            'category_id' => $request->category_id,
            'product_name' => $request->product_name,
            'price' => $request->price,
            'stock' => $request->stock,
            'unit' => $request->unit,
        ]);

        return redirect('/product')
            ->with('success', 'Produk berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = products::findOrFail($id);

        if ($product->detailtransactions()->exists()) {
            return redirect('/product')
                ->with('error', 'Produk tidak bisa dihapus karena sudah memiliki transaksi.');
        }

        $product->delete();

        return redirect('/product')
            ->with('success', 'Produk berhasil dihapus.');
    }
}