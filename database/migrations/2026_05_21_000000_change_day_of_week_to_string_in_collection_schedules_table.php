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
        Schema::table('collection_schedules', function (Blueprint $table) {
            $table->string('day_of_week')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('collection_schedules', function (Blueprint $table) {
            $table->enum('day_of_week', ['monday','tuesday','wednesday','thursday','friday','saturday','sunday'])->change();
        });
    }
};
