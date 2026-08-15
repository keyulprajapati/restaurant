<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KotItem extends Model
{
    protected $fillable = [
        'kot_id',
        'order_item_id',
        'item_name',
        'size',
        'quantity',
        'notes',
        'status',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
    ];

    public function kot()
    {
        return $this->belongsTo(Kot::class);
    }

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }
}