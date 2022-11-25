<?php

namespace App\Exports;

use App\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class StudentInfoTemplateExport implements FromCollection,WithHeadings, ShouldAutoSize
{
    private $sessionYearInitial, $sessionYearFinal, $semesterName, $department_id, $level;

    public function __construct($sessionYearInitial, $sessionYearFinal, $semesterName, $department_id, $level)
    {
        $this->sessionYearInitial = $sessionYearInitial;
        $this->sessionYearFinal = $sessionYearFinal;
        $this->semesterName = $semesterName;
        $this->department_id = $department_id;
        $this->level = $level;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Student::select('name','matricNo')->where([
            'sessionYearInitial'=>$this->sessionYearInitial,
            'sessionYearFinal' => $this->sessionYearFinal,
            'semesterName'=>$this->semesterName,
            'department_id'=>$this->department_id,
            'level'=>$this->level
        ])->get();
    }

    public function headings(): array
    {
        return [
            'Name',
            'Matric No',
            'Att',
            'Test',
            'Exam',
            'Total'
        ];
    }
}
