<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('collection_point_schedule', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('schedule_id')->constrained('collection_schedules')->cascadeOnDelete();
            $table->foreignUuid('collection_point_id')->constrained('collection_points')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collection_point_schedule');
    }
};
