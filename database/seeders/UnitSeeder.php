<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    public function run(): void
    {
        $units = [
            [
                'name' => 'Pieces',
                'short_code' => 'pcs',
                'description' => 'Countable individual items or pieces.',
                'is_active' => true,
            ],
            [
                'name' => 'Gram',
                'short_code' => 'gram',
                'description' => 'Weight measurement in grams.',
                'is_active' => true,
            ],
            [
                'name' => 'KG',
                'short_code' => 'kg',
                'description' => 'Weight measurement in kilograms.',
                'is_active' => true,
            ],
            [
                'name' => 'Glass',
                'short_code' => 'glass',
                'description' => 'Beverages, juices, shakes or drinks served by glass.',
                'is_active' => true,
            ],
            [
                'name' => 'Plate',
                'short_code' => 'plate',
                'description' => 'Dishes or meals served per plate.',
                'is_active' => true,
            ],
            [
                'name' => 'Bottle',
                'short_code' => 'bottle',
                'description' => 'Packaged drinks or bottles.',
                'is_active' => true,
            ],
            [
                'name' => 'Cup',
                'short_code' => 'cup',
                'description' => 'Hot drinks like tea, coffee, or soups served per cup.',
                'is_active' => true,
            ],
            [
                'name' => 'Litre',
                'short_code' => 'ltr',
                'description' => 'Liquid volume in litres.',
                'is_active' => true,
            ],
            [
                'name' => 'ML',
                'short_code' => 'ml',
                'description' => 'Liquid volume in millilitres.',
                'is_active' => true,
            ],
        ];

        foreach ($units as $unit) {
            Unit::firstOrCreate(
                ['short_code' => $unit['short_code']],
                [
                    'name' => $unit['name'],
                    'description' => $unit['description'],
                    'is_active' => $unit['is_active'],
                ]
            );
        }
    }
}
