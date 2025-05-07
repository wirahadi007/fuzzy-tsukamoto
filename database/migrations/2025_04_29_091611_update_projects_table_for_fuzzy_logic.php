<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('difficulty_level');
            $table->integer('employee_count')->after('deadline');
            $table->integer('processing_time')->change(); // This will store hours instead of days
        });
    }

    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->integer('difficulty_level');
            $table->dropColumn('employee_count');
            $table->integer('processing_time')->change();
        });
    }
};