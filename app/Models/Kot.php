<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kot extends Model
{
    protected $fillable = [
        'kot_number',
        'order_id',
        'restaurant_table_id',
        'status',
        'notes',
        'sent_at',
        'prepared_at',
        'ready_at',
        'served_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'prepared_at' => 'datetime',
        'ready_at' => 'datetime',
        'served_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function table()
    {
        return $this->belongsTo(
            RestaurantTable::class,
            'restaurant_table_id'
        );
    }

    public function items()
    {
        return $this->hasMany(KotItem::class);
    }
}