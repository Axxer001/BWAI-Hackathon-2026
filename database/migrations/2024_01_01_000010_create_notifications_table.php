<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->uuid('session_point_log_id')->nullable();
            $table->foreign('session_point_log_id')->references('id')->on('session_point_logs')->nullOnDelete();
            $table->enum('type', ['truck_arriving', 'collection_done', 'schedule_change', 'missed_alert', 'violation_update']);
            $table->enum('channel', ['sms', 'push', 'email']);
            $table->text('message');
            $table->boolean('is_sent')->default(false);
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
