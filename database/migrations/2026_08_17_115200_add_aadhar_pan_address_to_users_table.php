<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('aadhar_number', 20)
                ->nullable()
                ->after('email');

            $table->string('pan_number', 20)
                ->nullable()
                ->after('aadhar_number');

            $table->text('address')
                ->nullable()
                ->after('pan_number');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'aadhar_number',
                'pan_number',
                'address',
            ]);
        });
    }
};