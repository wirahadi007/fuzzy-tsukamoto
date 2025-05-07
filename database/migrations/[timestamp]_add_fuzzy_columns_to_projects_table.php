<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->integer('participants_count')->default(1);
            $table->integer('working_hours')->default(5);
            $table->integer('priority_scale')->default(1);
        });
    }

    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['participants_count', 'working_hours', 'priority_scale']);
        });
    }
};