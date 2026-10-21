<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id',
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

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

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

        $unitDisplay = $this->unitRelation?->name ?? $this->unit;

        return $size . ' ' . $unitDisplay;
    }

    public function unitRelation()
    {
        return $this->belongsTo(Unit::class, 'unit', 'short_code');
    }

    public function comboItems()
    {
        return $this->hasMany(ComboItem::class);
    }
}