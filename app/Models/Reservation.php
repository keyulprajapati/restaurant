<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
    'restaurant_table_id',
    'customer_id',
    'reservation_date',
    'reservation_time',
    'guests',
    'duration_minutes',
    'status',
    'special_request',
    'notes',
];

    protected $casts = [
        'reservation_date' => 'date',
        'guests' => 'integer',
        'duration_minutes' => 'integer',
    ];

    public function customer()
{
    return $this->belongsTo(
        Customer::class
    );
}

    public function table()
    {
        return $this->belongsTo(
            RestaurantTable::class,
            'restaurant_table_id'
        );
    }

    public function getDateTimeAttribute()
    {
        return Carbon::parse(
            $this->reservation_date->format('Y-m-d')
            . ' ' .
            (string) $this->reservation_time
        );
    }

    public function getEndDateTimeAttribute()
    {
        return $this->date_time
            ->copy()
            ->addMinutes(
                (int) $this->duration_minutes
            );
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {

            'pending' => 'Pending',

            'confirmed' => 'Confirmed',

            'seated' => 'Seated',

            'completed' => 'Completed',

            'cancelled' => 'Cancelled',

            'no_show' => 'No Show',

            default => ucfirst($this->status),

        };
    }
}