<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('ai_garbage_logs')) {
            Schema::create('ai_garbage_logs', function (Blueprint $table) {
                $table->uuid('id')->primary()->default(DB::raw('(UUID())'));
                $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();
                $table->foreignUuid('garbage_point_id')->nullable()->constrained('garbage_points')->nullOnDelete();
                $table->string('image_url');
                $table->text('ai_advice');
                $table->string('garbage_type')->nullable();
                $table->timestamps();

                $table->index(['user_id', 'created_at']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_garbage_logs');
    }
};