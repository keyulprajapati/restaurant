<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kot_items', function (Blueprint $table) {

            $table->id();

            $table->foreignId('kot_id')
                ->constrained('kots')
                ->cascadeOnDelete();

            $table->foreignId('order_item_id')
                ->nullable()
                ->constrained('order_items')
                ->nullOnDelete();

            $table->string('item_name');

            $table->string('size')->nullable();

            $table->decimal('quantity', 10, 2);

            $table->text('notes')->nullable();

            $table->enum('status', [
                'pending',
                'preparing',
                'ready',
                'served',
                'cancelled'
            ])->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kot_items');
    }
};