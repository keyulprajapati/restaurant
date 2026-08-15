<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {

            $table->id();

            $table->string('order_number', 30)->unique();

            $table->foreignId('customer_id')
                ->nullable()
                ->constrained('customers')
                ->nullOnDelete();

            $table->foreignId('restaurant_table_id')
                ->nullable()
                ->constrained('restaurant_tables')
                ->nullOnDelete();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->enum('order_type', [
                'dine_in',
                'takeaway',
                'delivery'
            ])->default('dine_in');

            $table->enum('status', [
                'draft',
                'placed',
                'preparing',
                'ready',
                'served',
                'completed',
                'cancelled'
            ])->default('draft');

            $table->decimal('subtotal', 12, 2)->default(0);

            $table->decimal('discount', 12, 2)->default(0);

            $table->decimal('tax', 12, 2)->default(0);

            $table->decimal('grand_total', 12, 2)->default(0);

            $table->text('notes')->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};