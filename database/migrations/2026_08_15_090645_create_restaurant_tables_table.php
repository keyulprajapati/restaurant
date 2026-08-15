<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('restaurant_tables', function (Blueprint $table) {
            $table->id();

            $table->string('table_number', 50);

            $table->string('name', 100)->nullable();

            $table->unsignedInteger('capacity')->default(2);

            $table->string('area', 100)->nullable();

            $table->enum('table_type', [
                'regular',
                'round',
                'square',
                'outdoor',
                'private',
            ])->default('regular');

            /*
             * Current/live status of the table.
             */
            $table->enum('status', [
                'available',
                'occupied',
                'reserved',
            ])->default('available');

            /*
             * Configuration status.
             */
            $table->boolean('is_active')->default(true);

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index('table_number');
            $table->index('status');
            $table->index('is_active');
            $table->index('area');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('restaurant_tables');
    }
};