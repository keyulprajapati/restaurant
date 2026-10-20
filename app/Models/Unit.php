<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'short_code',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'unit', 'short_code');
    }

    public function comboItems()
    {
        return $this->hasMany(ComboItem::class, 'unit', 'short_code');
    }

    public function isInUse(): bool
    {
        return $this->products()->exists() || $this->comboItems()->exists();
    }
}
