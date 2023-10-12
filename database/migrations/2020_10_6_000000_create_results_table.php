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
        Schema::create('results', function (Blueprint $table) {
            $table->increments('id');
            $table->datetime('submission');
            $table->integer('score');
            $table->char('grade', 1);
            $table->double('grade_point', 4, 2);
            $table->enum('status',['unapproved', 'approved'])->default('unapproved');
            $table->enum('publish',['unpublish', 'published'])->default('unpublish');
            $table->unsignedInteger('course_id')->index();
            $table->unsignedInteger('student_id')->index();

            $table->foreign('course_id')
            ->references('id')
            ->on('courses')
            ->onDelete('cascade');

            $table->foreign('student_id')
            ->references('id')
            ->on('students')
            ->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};
