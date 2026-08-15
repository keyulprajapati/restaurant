<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'price',
        'size',
        'unit',
        'food_type',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'size' => 'decimal:3',
        'is_active' => 'boolean',
    ];

    public function getFormattedSizeAttribute(): string
    {
        if (!$this->size) {
            return '-';
        }

        $size = rtrim(
            rtrim(number_format(
                (float) $this->size,
                3,
                '.',
                ''
            ), '0'),
            '.'
        );

        return $size . ' ' . $this->unit;
    }

    public function comboItems()
    {
        return $this->hasMany(ComboItem::class);
    }
}