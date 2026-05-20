<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('truck_full_events', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('session_id');
            $table->foreign('session_id')->references('id')->on('collection_sessions')->cascadeOnDelete();
            $table->uuid('collection_point_id');
            $table->foreign('collection_point_id')->references('id')->on('collection_points')->cascadeOnDelete();
            $table->timestamp('logged_at')->useCurrent();
            $table->string('dumping_site')->nullable();
            $table->enum('resume_status', ['pending', 'resumed', 'cancelled'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('truck_full_events');
    }
};
