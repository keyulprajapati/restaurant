<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {

            $table->dropColumn([
                'customer_name',
                'phone',
                'email',
            ]);

        });
    }

    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {

            $table->string('customer_name', 150);
            $table->string('phone', 30);
            $table->string('email', 150)->nullable();

        });
    }
};