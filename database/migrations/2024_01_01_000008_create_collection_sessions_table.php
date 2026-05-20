<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('collection_sessions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('schedule_id');
            $table->foreign('schedule_id')->references('id')->on('collection_schedules')->cascadeOnDelete();
            $table->uuid('collector_id');
            $table->foreign('collector_id')->references('id')->on('users')->restrictOnDelete();
            $table->uuid('truck_id');
            $table->foreign('truck_id')->references('id')->on('trucks')->restrictOnDelete();
            $table->date('session_date');
            $table->enum('status', ['pending', 'ongoing', 'completed', 'missed', 'cancelled'])->default('pending');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('collection_sessions');
    }
};
