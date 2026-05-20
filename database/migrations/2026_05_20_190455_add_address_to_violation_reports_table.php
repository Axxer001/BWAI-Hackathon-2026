<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('violation_reports', function (Blueprint $table) {
            // Adds the address column right after the barangay_id column
            $table->string('address')->after('barangay_id'); 
        });
    }

    public function down()
    {
        Schema::table('violation_reports', function (Blueprint $table) {
            $table->dropColumn('address');
        });
    }
};
