<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class StudentResultWithCgpaExport implements FromView, ShouldAutoSize
{
    private $studentResult, $resultInfo, $tableHeader, $analysis;

    public function __construct($studentResult,$resultInfo,$tableHeader,$analysis) {
        $this->studentResult = $studentResult;
        $this->resultInfo = $resultInfo;
        $this->tableHeader = $tableHeader;
        $this->analysis = $analysis;
    }

    public function view(): View
    {
        return view( 'modules/exports/collegeOfficerDepartmentLevelResult',[
            'studentResults' => $this->studentResult,
            'resultInfo' => $this->resultInfo,
            'tableHeaders' => $this->tableHeader,
            'analysis' => $this->analysis
        ]);
    }
}
