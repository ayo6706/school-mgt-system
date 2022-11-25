<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DepartmentTakingRegisteredCourse extends Model
{
    protected $fillable = ['registered_course_id','department_id'];
}
