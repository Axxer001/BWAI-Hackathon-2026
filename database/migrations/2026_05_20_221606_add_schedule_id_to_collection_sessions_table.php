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
        Schema::table('collection_sessions', function (Blueprint $table) {
            // 1. Add the UUID column (using after() keeps your database organized next to the ID)
            $table->uuid('schedule_id')->after('id');

            // 2. Add the foreign key constraint pointing to the collection_schedules table
            $table->foreign('schedule_id')
                  ->references('id')
                  ->on('collection_schedules')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('collection_sessions', function (Blueprint $table) {
            // Drop the foreign key first, then drop the column
            $table->dropForeign(['schedule_id']);
            $table->dropColumn('schedule_id');
        });
    }
};