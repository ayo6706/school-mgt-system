<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DepartmentalLevelResult extends Model
{
    protected $fillable = ["sessionYearInitial","sessionYearFinal","semesterName","department_id","level","name","matricNo","tp","tu","gpa","remark"];
}
