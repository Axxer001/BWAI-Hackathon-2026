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
        Schema::create('collection_schedule_garbage_point', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('schedule_id')->constrained('collection_schedules')->cascadeOnDelete();
            $table->foreignUuid('garbage_point_id')->constrained('garbage_points')->cascadeOnDelete();
            $table->unsignedInteger('sequence')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collection_schedule_garbage_point');
    }
};
