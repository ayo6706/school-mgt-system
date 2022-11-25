<?php

namespace App\Imports;

use App\Student;
use App\StudentResult;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class StudentResultImport implements ToModel, WithHeadingRow, WithCalculatedFormulas
{

    private $semesterName, $sessionYearInitial,$sessionYearFinal, $department_id, $level, $registered_course_id;

    public function __construct($semesterName, $sessionYearInitial, $sessionYearFinal, $department_id, $level, $registered_course_id)
    {
        $this->semesterName = $semesterName;
        $this->sessionYearInitial = $sessionYearInitial;
        $this->sessionYearFinal = $sessionYearFinal;
        $this->department_id = $department_id;
        $this->level = $level;
        $this->registered_course_id = $registered_course_id;
    }
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $matricNo = $row['matric_no'];

        $student_id = Student::where([
            'sessionYearInitial'=>$this->sessionYearInitial,
            'sessionYearFinal'=>$this->sessionYearFinal,
            'semesterName'=>$this->semesterName,
            'department_id'=>$this->department_id,
            'level'=>$this->level,
            'matricNo' =>$matricNo
        ])->value('id');

        return new StudentResult([
            'student_id' => $student_id,
            'registered_course_id' => $this->registered_course_id,
            'att' => $row['att'],
            'test' => $row['test'],
            'exam' => $row['exam'],
            'total' => $row['total'],
            'grade' => StudentResult::generateGrade($row['total'])
        ]);
    }
}
