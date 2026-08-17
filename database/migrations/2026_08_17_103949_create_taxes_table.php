<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('taxes', function (Blueprint $table) {
            $table->id();

            $table->string('name', 100);

            $table->string('code', 50);

            $table->enum('type', [
                'percentage',
                'fixed',
            ]);

            $table->decimal('rate', 10, 2);

            $table->enum('applies_to', [
                'all',
                'food',
                'beverage',
                'service',
            ])->default('all');

            $table->string('description', 500)->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->unique('code');

            $table->index('name');
            $table->index('type');
            $table->index('applies_to');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('taxes');
    }
};