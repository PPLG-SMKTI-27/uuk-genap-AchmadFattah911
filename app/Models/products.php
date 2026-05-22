<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class products extends Model
{
    protected $fillable = [
        'category_id',
        'product_name',
        'price',
        'stock',
        'unit',
    ];

    public function category()
    {
        return $this->belongsTo(categories::class);
    }

    public function detailtransactions()
    {
        return $this->hasMany(transactions_details::class, 'product_id');
    }
}