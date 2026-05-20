<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('missed_collection_reports', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('session_id');
            $table->foreign('session_id')->references('id')->on('collection_sessions')->cascadeOnDelete();
            $table->uuid('collection_point_id');
            $table->foreign('collection_point_id')->references('id')->on('collection_points')->cascadeOnDelete();
            $table->uuid('reported_by');
            $table->foreign('reported_by')->references('id')->on('users')->cascadeOnDelete();
            $table->string('photo_url')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'acknowledged', 'resolved'])->default('pending');
            $table->timestamp('reported_at')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('missed_collection_reports');
    }
};
