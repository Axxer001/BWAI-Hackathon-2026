<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('collection_reports')) {
            Schema::create('collection_reports', function (Blueprint $table) {
                $table->uuid('id')->primary()->default(DB::raw('(UUID())'));
                $table->foreignUuid('session_id')->unique()->constrained('collection_sessions')->cascadeOnDelete();
                $table->unsignedInteger('total_points')->default(0);
                $table->unsignedInteger('completed_points')->default(0);
                $table->unsignedInteger('total_notified_users')->default(0);
                $table->text('notes')->nullable();
                $table->timestamp('generated_at')->useCurrent();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('collection_reports');
    }
};