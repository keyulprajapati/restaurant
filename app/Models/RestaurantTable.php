<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestaurantTable extends Model
{
    protected $table = 'restaurant_tables';

    protected $fillable = [
        'table_number',
        'name',
        'capacity',
        'area',
        'table_type',
        'status',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'capacity' => 'integer',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeAvailable($query)
    {
        return $query
            ->where('is_active', true)
            ->where('status', 'available');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'available' => 'Available',
            'occupied' => 'Occupied',
            'reserved' => 'Reserved',
            default => ucfirst($this->status),
        };
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->table_type) {
            'regular' => 'Regular',
            'round' => 'Round',
            'square' => 'Square',
            'outdoor' => 'Outdoor',
            'private' => 'Private',
            default => ucfirst($this->table_type),
        };
    }

    public function reservations()
    {
        return $this->hasMany(
            Reservation::class,
            'restaurant_table_id'
        );
    }

    public function orders()
    {
        return $this->hasMany(
            Order::class,
            'restaurant_table_id'
        );
    }

    public function activeOrder()
    {
        return $this->hasOne(Order::class, 'restaurant_table_id')
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->latestOfMany();
    }

    public function getUrlAttribute(): string
    {
        // 1. If explicit QR base URL is configured in settings, use it
        $configuredBase = function_exists('setting') ? setting('qr_base_url') : null;
        if (!empty($configuredBase)) {
            $base = rtrim($configuredBase, '/');
            return $base . '/table/' . $this->table_number;
        }

        // 2. If running via HTTP request, resolve the accessible network root URL
        if (request() && request()->root()) {
            $root = request()->root();
            if (str_contains($root, 'localhost') || str_contains($root, '127.0.0.1')) {
                $hostIp = gethostbyname(gethostname());
                if (!empty($hostIp) && $hostIp !== '127.0.0.1') {
                    $root = str_replace(['localhost', '127.0.0.1'], $hostIp, $root);
                }
            }
            return rtrim($root, '/') . '/table/' . $this->table_number;
        }

        // 3. Fallback to url()
        $url = url('/table/' . $this->table_number);
        if (str_contains($url, 'localhost') || str_contains($url, '127.0.0.1')) {
            $hostIp = gethostbyname(gethostname());
            if (!empty($hostIp) && $hostIp !== '127.0.0.1') {
                $url = str_replace(['localhost', '127.0.0.1'], $hostIp, $url);
            }
        }
        return $url;
    }
}