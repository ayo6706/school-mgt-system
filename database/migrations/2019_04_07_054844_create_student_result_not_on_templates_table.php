<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentResultNotOnTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_result_not_on_templates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sessionYearInitial');
            $table->string('sessionYearFinal');
            $table->enum('semesterName',['first', 'second', 'harmattan', 'rain']);
            $table->integer('department_id');
            $table->string('level');
            $table->integer('registered_course_id');
            $table->string('name');
            $table->string('matricNo');
            $table->decimal('att');
            $table->decimal('test');
            $table->decimal('exam');
            $table->decimal('total');
            $table->string('grade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_result_not_on_templates');
    }
}
