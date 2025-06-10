<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('course_student', function (Blueprint $table) {
            $table->unique(['course_id', 'student_id', 'semester'], 'course_student_unique');
        });
    }

    public function down()
    {
        Schema::table('course_student', function (Blueprint $table) {
            $table->dropUnique('course_student_unique');
        });
    }
};