<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('restaurant_tables', function (Blueprint $table) {
            $table->dropIndex('restaurant_tables_table_number_index');
            $table->unique(['branch_id', 'table_number']);
        });
    }

    public function down(): void
    {
        Schema::table('restaurant_tables', function (Blueprint $table) {
            $table->dropUnique('restaurant_tables_branch_id_table_number_unique');
            $table->index('table_number');
        });
    }
};
