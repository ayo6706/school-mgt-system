<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentResult extends Model
{
    protected $fillable = ['student_id','registered_course_id','att','test','exam','total','grade'];

    public static function generateGrade($score) {
        $result = '';
        if($score === null) {
            $result = '-';
        } else if($score < 40) {
            $result = 'F';
        } else if(($score >= 40)&&($score < 45)) {
            $result = 'E';
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
