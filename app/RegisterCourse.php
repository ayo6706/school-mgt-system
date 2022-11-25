<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RegisterCourse extends Model
{
    protected $fillable = ['lecturer_id','sessionYearInitial','sessionYearFinal','semesterName','courseName','courseCode'];
}
