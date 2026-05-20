<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('session_points', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('(UUID())'));
            $table->foreignUuid('session_id')->constrained('collection_sessions')->cascadeOnDelete();
            $table->foreignUuid('garbage_point_id')->constrained('garbage_points')->cascadeOnDelete();
            $table->unsignedInteger('route_order');
            $table->enum('status', ['pending', 'notified', 'collected', 'skipped'])->default('pending');
            $table->timestamp('notified_at')->nullable();
            $table->timestamp('collected_at')->nullable();
            $table->timestamps();

            $table->unique(['session_id', 'garbage_point_id']);
            $table->index(['session_id', 'route_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('session_points');
    }
};