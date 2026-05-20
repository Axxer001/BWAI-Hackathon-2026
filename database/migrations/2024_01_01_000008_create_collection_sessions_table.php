<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('collection_sessions', function (Blueprint $table) {
            // 1. Primary Key UUID
            $table->char('id', 36)->primary()->default(DB::raw('(UUID())'));
            
            // 2. Relational Foreign Keys (char(36) matching UUID formats)
            $table->char('barangay_id', 36);
            $table->char('schedule_id', 36);
            $table->char('collector_id', 36);
            $table->char('truck_id', 36);
            
            // 3. Session Metrics & Lifecycles
            $table->date('session_date');
            $table->enum('status', ['pending', 'ongoing', 'completed', 'cancelled'])->default('pending');
            
            // 4. Time Tracks & System Stamps
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->timestamps();

            // 5. Explicit Foreign Constraints (Keeps data clean and synced)
            $table->foreign('barangay_id')->references('id')->on('barangays')->onDelete('cascade');
            $table->foreign('schedule_id')->references('id')->on('collection_schedules')->onDelete('cascade');
            $table->foreign('collector_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('truck_id')->references('id')->on('trucks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collection_sessions');
    }
};