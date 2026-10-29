<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('products', 'sequence_number')) {
            Schema::table('products', function (Blueprint $table) {
                $table->unsignedSmallInteger('sequence_number')->default(1);
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('products', 'sequence_number')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('sequence_number');
            });
        }
    }
};
