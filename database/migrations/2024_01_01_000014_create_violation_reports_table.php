<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('violation_reports', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('reported_by');
            $table->foreign('reported_by')->references('id')->on('users')->cascadeOnDelete();
            $table->uuid('barangay_id');
            $table->foreign('barangay_id')->references('id')->on('barangays')->cascadeOnDelete();
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->string('photo_url')->nullable();
            $table->text('description');
            $table->enum('status', ['pending', 'under_review', 'fined', 'dismissed'])->default('pending');
            $table->timestamp('reported_at')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('violation_reports');
    }
};
