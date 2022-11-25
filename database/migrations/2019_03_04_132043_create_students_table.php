<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('matricNo');
            $table->string('sessionYearInitial');
            $table->string('sessionYearFinal');
            $table->enum('semesterName', ['first', 'second', 'harmattan', 'rain']);
//            $table->integer('college_id');
//            $table->string('collegeName');
            $table->integer('department_id');
            $table->string('departmentName');
            $table->string('level');
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
        Schema::dropIfExists('students');
    }
}
