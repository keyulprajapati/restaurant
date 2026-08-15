<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kots', function (Blueprint $table) {

            $table->id();

            $table->string('kot_number', 30)->unique();

            $table->foreignId('order_id')
                ->constrained('orders')
                ->cascadeOnDelete();

            $table->foreignId('restaurant_table_id')
                ->nullable()
                ->constrained('restaurant_tables')
                ->nullOnDelete();

            $table->enum('status', [
                'pending',
                'preparing',
                'ready',
                'served',
                'cancelled'
            ])->default('pending');

            $table->text('notes')->nullable();

            $table->timestamp('sent_at')->nullable();

            $table->timestamp('prepared_at')->nullable();

            $table->timestamp('ready_at')->nullable();

            $table->timestamp('served_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kots');
    }
};