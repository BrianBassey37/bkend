<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->unsignedInteger('school_id')->index();
            $table->unsignedInteger('dept_id')->index();

            $table->foreign('school_id')
            ->references('id')
            ->on('schools')
            ->onDelete('cascade');

            $table->foreign('dept_id')
            ->references('id')
            ->on('departments')
            ->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
