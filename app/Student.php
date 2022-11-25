<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = ['name','matricNo','sessionYearInitial','sessionYearFinal','semesterName','department_id','departmentName','level'];
}
