<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDepartmentalLevelResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('departmental_level_results', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sessionYearInitial');
            $table->integer('sessionYearFinal');
            $table->enum('semesterName',['first', 'second', 'harmattan', 'rain']);
            $table->integer('department_id');
            $table->string('level');
            $table->string('name');
            $table->string('matricNo');
            $table->decimal('tp')->nullable();
            $table->decimal('tu')->nullable();
            $table->decimal('gpa')->nullable();
            $table->string('remark')->nullable();
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
        Schema::dropIfExists('departmental_level_results');
    }
}
