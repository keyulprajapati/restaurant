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
        /*
         * Jobs
         */
        Schema::create('jobs', function (Blueprint $table) {

            $table->id();

            // Limited to 100 characters because of old
            // MySQL/MariaDB index limitations.
            $table->string('queue', 100)->index();

            $table->longText('payload');

            $table->unsignedSmallInteger('attempts');

            $table->unsignedInteger('reserved_at')->nullable();

            $table->unsignedInteger('available_at');

            $table->unsignedInteger('created_at');
        });


        /*
         * Job Batches
         */
        Schema::create('job_batches', function (Blueprint $table) {

            $table->string('id', 100)->primary();

            $table->string('name');

            $table->integer('total_jobs');

            $table->integer('pending_jobs');

            $table->integer('failed_jobs');

            $table->longText('failed_job_ids');

            $table->mediumText('options')->nullable();

            $table->integer('cancelled_at')->nullable();

            $table->integer('created_at');

            $table->integer('finished_at')->nullable();
        });


        /*
         * Failed Jobs
         */
        Schema::create('failed_jobs', function (Blueprint $table) {

            $table->id();

            /*
             * Keep UUID smaller because it has a unique index.
             */
            $table->string('uuid', 100)->unique();

            /*
             * These fields are part of a composite index.
             * Keep them at 100 characters to stay below
             * the 1000-byte index limitation.
             */
            $table->string('connection', 100);

            $table->string('queue', 100);

            $table->longText('payload');

            $table->longText('exception');

            $table->timestamp('failed_at')->useCurrent();

            $table->index(
                ['connection', 'queue', 'failed_at'],
                'failed_jobs_connection_queue_failed_at_idx'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('failed_jobs');

        Schema::dropIfExists('job_batches');

        Schema::dropIfExists('jobs');
    }
};