<?php

namespace App\Imports;

use App\DepartmentalLevelResult;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class DepartmentalLevelResultImport implements ToModel, WithHeadingRow, WithCalculatedFormulas
{
    private $semesterName, $sessionYearInitial,$sessionYearFinal, $department_id, $level;

    public function __construct($semesterName, $sessionYearInitial, $sessionYearFinal, $department_id, $level)
    {
        $this->semesterName = $semesterName;
        $this->sessionYearInitial = $sessionYearInitial;
        $this->sessionYearFinal = $sessionYearFinal;
        $this->department_id = $department_id;
        $this->level = $level;
    }

    public function model(array $row)
    {
        return new DepartmentalLevelResult([
            'sessionYearInitial' => $this->sessionYearInitial,
            'sessionYearFinal' => $this->sessionYearFinal,
            'semesterName' => $this->semesterName,
            'department_id' => $this->department_id,
            'level' => $this->level,
            'name' => $row['name'],
            'matricNo' => $row['matric_no'],
            'tp' => $row['tp'],
            'tu' => $row['tu'],
            'gpa' => $row['gpa'],
            'remark' => $row['remarks']
        ]);
    }

    public function headingRow(): int
    {
        return 9;
    }
}
