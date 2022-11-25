<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class StudentResultNotOnTemplateResultExport implements FromView, ShouldAutoSize
{
    private $studentResult, $resultInfo;

    public function __construct($studentResult,$resultInfo) {
        $this->studentResult = $studentResult;
        $this->resultInfo = $resultInfo;
    }

    public function view(): View
    {
        return view('modules/exports/studentResultNotOnTemplate', [
            'studentResults' => $this->studentResult,
            'resultInfo' => $this->resultInfo
        ]);
    }
}
