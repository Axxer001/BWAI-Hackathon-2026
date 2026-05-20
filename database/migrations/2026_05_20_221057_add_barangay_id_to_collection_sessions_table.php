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
        Schema::table('collection_sessions', function (Blueprint $table) {
            $table->uuid('barangay_id')->nullable()->after('id'); // Adjust type (uuid vs foreignId) based on your DB setup
        });
    }

    public function down()
    {
        Schema::table('collection_sessions', function (Blueprint $table) {
            $table->dropColumn('barangay_id');
        });
    }
};
