<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('session_point_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('session_id');
            $table->foreign('session_id')->references('id')->on('collection_sessions')->cascadeOnDelete();
            $table->uuid('collection_point_id');
            $table->foreign('collection_point_id')->references('id')->on('collection_points')->cascadeOnDelete();
            $table->enum('status', ['pending', 'collected', 'skipped'])->default('pending');
            $table->string('photo_url')->nullable();
            $table->decimal('gps_lat', 10, 7)->nullable();
            $table->decimal('gps_lng', 10, 7)->nullable();
            $table->timestamp('collected_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('session_point_logs');
    }
};
