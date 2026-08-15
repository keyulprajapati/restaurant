<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {

            $table->id();

            $table->foreignId('restaurant_table_id')
                ->nullable()
                ->constrained('restaurant_tables')
                ->nullOnDelete();

            $table->string('customer_name', 150);

            $table->string('phone', 30);

            $table->string('email', 150)->nullable();

            $table->date('reservation_date');

            $table->time('reservation_time');

            $table->unsignedInteger('guests')->default(1);

            $table->unsignedInteger('duration_minutes')->default(90);

            $table->enum('status', [
                'pending',
                'confirmed',
                'seated',
                'completed',
                'cancelled',
                'no_show',
            ])->default('pending');

            $table->text('special_request')->nullable();

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index([
                'reservation_date',
                'reservation_time'
            ]);

            $table->index('status');
            $table->index('phone');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};