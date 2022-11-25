<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentResultNotOnTemplate extends Model
{
    protected $fillable = ['sessionYearInitial','sessionYearFinal','semesterName','department_id','level','registered_course_id','name','matricNo','att','test','exam','total','grade'];

    public static function generateGrade($score) {
        $result = '';
        if($score < 44) {
            $result = 'F';
        } else if(($score >= 45) &&($score < 50)) {
            $result = 'D';
        } else if(($score >=50) && ($score < 60)) {
            $result = 'C';
        } else if(($score >= 60) && ($score < 70)) {
            $result = 'B';
        } else if($score >= 70)  {
            $result = 'A';
        }
        return $result;
    }
}
