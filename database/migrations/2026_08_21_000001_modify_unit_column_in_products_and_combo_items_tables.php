<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('unit', 50)->default('pcs')->change();
        });

        Schema::table('combo_items', function (Blueprint $table) {
            $table->string('unit', 50)->default('pcs')->change();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->enum('unit', ['kg', 'gram', 'pcs'])->default('pcs')->change();
        });

        Schema::table('combo_items', function (Blueprint $table) {
            $table->enum('unit', ['kg', 'gram', 'pcs'])->default('pcs')->change();
        });
    }
};
