<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('kot_items', function (Blueprint $table) {
            if (!Schema::hasColumn('kot_items', 'unit')) {
                $table->string('unit')->nullable()->after('size');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kot_items', function (Blueprint $table) {
            if (Schema::hasColumn('kot_items', 'unit')) {
                $table->dropColumn('unit');
            }
        });
    }
};
