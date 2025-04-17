<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('client_name');
            $table->string('division');
            $table->date('deadline');
            $table->integer('difficulty_level'); // 1-5 scale
            $table->integer('priority_level'); // 1-5 scale
            $table->integer('processing_time'); // in days
            $table->foreignId('assigned_to')->constrained('users');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('projects');
    }
};