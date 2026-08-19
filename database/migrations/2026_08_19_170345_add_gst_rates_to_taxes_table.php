<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('taxes', function (Blueprint $table) {
            $table->decimal('cgst_rate', 8, 2)
                ->nullable()
                ->after('rate');

            $table->decimal('sgst_rate', 8, 2)
                ->nullable()
                ->after('cgst_rate');

            $table->decimal('igst_rate', 8, 2)
                ->nullable()
                ->after('sgst_rate');
        });
    }

    public function down(): void
    {
        Schema::table('taxes', function (Blueprint $table) {
            $table->dropColumn([
                'cgst_rate',
                'sgst_rate',
                'igst_rate',
            ]);
        });
    }
};