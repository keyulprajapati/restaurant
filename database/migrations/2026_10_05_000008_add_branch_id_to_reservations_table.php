<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->foreignId('branch_id')->nullable()->after('restaurant_table_id')->constrained()->nullOnDelete();
        });

        DB::table('reservations')
            ->whereNotNull('restaurant_table_id')
            ->get(['id', 'restaurant_table_id'])
            ->each(function ($reservation) {
                $branchId = DB::table('restaurant_tables')
                    ->where('id', $reservation->restaurant_table_id)
                    ->value('branch_id');

                if ($branchId) {
                    DB::table('reservations')
                        ->where('id', $reservation->id)
                        ->update(['branch_id' => $branchId]);
                }
            });
    }

    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropConstrainedForeignId('branch_id');
        });
    }
};