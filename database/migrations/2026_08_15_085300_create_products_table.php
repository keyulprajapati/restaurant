<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->string('name', 150);

            $table->decimal('price', 10, 2)
                ->default(0);

            $table->decimal('size', 10, 3)
                ->nullable();

            $table->enum('unit', [
                'kg',
                'gram',
                'pcs',
            ])->default('pcs');

            $table->enum('food_type', [
                'general',
                'jain',
            ])->default('general');

            $table->boolean('is_active')
                ->default(true);

            $table->timestamps();

            $table->index('is_active');
            $table->index('food_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};