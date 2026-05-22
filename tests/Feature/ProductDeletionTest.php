<?php

use App\Models\User;
use App\Models\categories;
use App\Models\products;
use App\Models\transactions;
use App\Models\transactions_details;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('product can be deleted when it has no transactions', function () {
    $user = User::factory()->create();

    // Create a category
    $category = categories::create([
        'category_name' => 'Makanan',
        'description' => 'Makanan berat',
    ]);

    // Create a product
    $product = products::create([
        'category_id' => $category->id,
        'product_name' => 'Nasi Goreng',
        'price' => 15000,
        'stock' => 10,
        'unit' => 'Pcs',
    ]);

    $response = $this
        ->actingAs($user)
        ->delete("/product/{$product->id}");

    $response
        ->assertRedirect('/product')
        ->assertSessionHas('success', 'Produk berhasil dihapus.');

    $this->assertDatabaseMissing('products', [
        'id' => $product->id,
    ]);
});

test('product cannot be deleted when it has transactions', function () {
    $user = User::factory()->create();

    // Create a category
    $category = categories::create([
        'category_name' => 'Elektronik',
        'description' => 'Barang elektronik',
    ]);

    // Create a product
    $product = products::create([
        'category_id' => $category->id,
        'product_name' => 'Mouse Gaming',
        'price' => 150000,
        'stock' => 5,
        'unit' => 'Pcs',
    ]);

    // Create a transaction
    $transaction = transactions::create([
        'transaction_no' => 'TRX-' . time(),
        'date' => now()->toDateString(),
        'customer_name' => 'Customer Umum',
        'total_price' => 150000,
        'status' => 'lunas',
    ]);

    // Create detail transaction
    transactions_details::create([
        'transaction_id' => $transaction->id,
        'product_id' => $product->id,
        'quantity' => 1,
        'unit_price' => 150000,
        'subtotal' => 150000,
    ]);

    $response = $this
        ->actingAs($user)
        ->delete("/product/{$product->id}");

    $response
        ->assertRedirect('/product')
        ->assertSessionHas('error', 'Produk tidak bisa dihapus karena sudah memiliki transaksi.');

    $this->assertDatabaseHas('products', [
        'id' => $product->id,
    ]);
});
