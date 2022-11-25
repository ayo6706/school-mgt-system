<?php

namespace App\Imports;

use App\Student;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentInfoTemplateImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    private $semesterName, $sessionYearInitial, $sessionYearFinal, $department_id, $departmentName, $level;

    public function __construct($semesterName, $sessionYearInitial, $sessionYearFinal, $department_id, $departmentName, $level)
    {
        $this->semesterName = $semesterName;
        $this->sessionYearInitial = $sessionYearInitial;
        $this->sessionYearFinal = $sessionYearFinal;
        $this->department_id = $department_id;
        $this->departmentName = $departmentName;
        $this->level = $level;
    }

    public function model(array $row)
    {
        return new Student([
            'name' => $row['name'],
            'matricNo' => $row['matric_no'],
            'sessionYearInitial' => $this->sessionYearInitial,
            'sessionYearFinal' => $this->sessionYearFinal,
            'semesterName' => $this->semesterName,
            'department_id' => $this->department_id,
            'departmentName' => $this->departmentName,
            'level' => $this->level
        ]);
    }

//    public function headingRow(): int
//    {
//        return 6;
//    }
}
