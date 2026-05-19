<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class transactions_details extends Model
{
    protected $table = 'transactions_details';

    protected $fillable = [
        'transaction_id',
        'product_id',
        'quantity',
        'unit_price',
        'subtotal',
    ];

    public function transaction()
    {
        return $this->belongsTo(transactions::class);
    }

    public function product()
    {
        return $this->belongsTo(products::class);
    }
}