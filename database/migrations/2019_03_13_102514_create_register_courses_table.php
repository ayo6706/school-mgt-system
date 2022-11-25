<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegisterCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('register_courses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('lecturer_id');
            $table->string('sessionYearInitial'); //This will be the year e.g 2018/2019
            $table->string('sessionYearFinal');
            $table->enum('semesterName',['first', 'second', 'harmattan', 'rain']);
            $table->string('courseName');
            $table->string('courseCode');
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
        Schema::dropIfExists('register_courses');
    }
}
