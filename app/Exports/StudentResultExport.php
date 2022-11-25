<?php

namespace App\Exports;

//use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class StudentResultExport implements FromView, ShouldAutoSize
{
    private $studentResult, $resultInfo, $analysis;

    public function __construct($studentResult,$resultInfo,$analysis) {
        $this->studentResult = $studentResult;
        $this->resultInfo = $resultInfo;
        $this->analysis = $analysis;
    }

    public function view(): View
    {
        return view('modules/exports/oneDepartmentSingleCourse', [
            'studentResults' => $this->studentResult,
            'resultInfo' => $this->resultInfo,
            'analysis' => $this->analysis
        ]);
    }
}
