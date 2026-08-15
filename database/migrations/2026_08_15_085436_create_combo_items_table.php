<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('combo_items', function (Blueprint $table) {

            $table->id();

            $table->foreignId('combo_id')
                ->constrained('combos')
                ->cascadeOnDelete();

            $table->foreignId('product_id')
                ->constrained('products')
                ->restrictOnDelete();

            $table->decimal('size', 10, 3)
                ->nullable();

            $table->enum('unit', [
                'kg',
                'gram',
                'pcs',
            ])->default('pcs');

            $table->decimal('quantity', 10, 3)
                ->default(1);

            $table->timestamps();

            $table->index([
                'combo_id',
                'product_id'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('combo_items');
    }
};