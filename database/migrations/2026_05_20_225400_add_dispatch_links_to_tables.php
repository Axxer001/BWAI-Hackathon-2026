<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */

    public function up()
    {
        // 1. Clean up botched columns from the previous failed attempt
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'assigned_truck_id')) {
                $table->dropColumn('assigned_truck_id');
            }
        });

        Schema::table('collection_schedules', function (Blueprint $table) {
            if (Schema::hasColumn('collection_schedules', 'default_collector_id')) {
                $table->dropColumn('default_collector_id');
            }
            if (Schema::hasColumn('collection_schedules', 'default_truck_id')) {
                $table->dropColumn('default_truck_id');
            }
        });

        // 2. Now add them properly with the correct UUID types
        Schema::table('users', function (Blueprint $table) {
            $table->foreignUuid('assigned_truck_id')->nullable()->constrained('trucks')->nullOnDelete();
        });

        Schema::table('collection_schedules', function (Blueprint $table) {
            $table->foreignUuid('default_collector_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignUuid('default_truck_id')->nullable()->constrained('trucks')->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['assigned_truck_id']);
            $table->dropColumn('assigned_truck_id');
        });

        Schema::table('collection_schedules', function (Blueprint $table) {
            $table->dropForeign(['default_collector_id']);
            $table->dropColumn('default_collector_id');
            $table->dropForeign(['default_truck_id']);
            $table->dropColumn('default_truck_id');
        });
    }

};
