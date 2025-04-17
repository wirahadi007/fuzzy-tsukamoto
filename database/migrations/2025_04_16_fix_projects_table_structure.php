<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::dropIfExists('projects');
        
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('client_name');
            $table->foreignId('division_id')->constrained();
            $table->date('deadline');
            $table->integer('difficulty_level');
            $table->integer('priority_level');
            $table->integer('processing_time');
            $table->foreignId('assigned_to')->constrained('users');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('projects');
    }
};