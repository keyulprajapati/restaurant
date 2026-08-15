<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComboItem extends Model
{
    protected $fillable = [
        'combo_id',
        'product_id',
        'size',
        'unit',
        'quantity',
    ];

    protected $casts = [
        'size' => 'decimal:3',
        'quantity' => 'decimal:3',
    ];

    public function combo()
    {
        return $this->belongsTo(Combo::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}