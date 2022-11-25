<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;

class StudentResultWithGpaExport implements FromView, ShouldAutoSize
{
    private $studentResult, $resultInfo, $tableHeader, $analysis;

    public function __construct($studentResult,$resultInfo,$tableHeader,$analysis) {
        $this->studentResult = $studentResult;
        $this->resultInfo = $resultInfo;
        $this->tableHeader = $tableHeader;
        $this->analysis = $analysis;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                $drawing->setName('Logo');
                $drawing->setDescription('Logo');
                $drawing->setPath(storage_path('/image/oduduwaUniversityLogo'));
                $drawing->setCoordinates('D1');

                $drawing->setWorksheet($event->sheet->getDelegate());
            },
        ];
    }

    public function view(): View
    {
        return view( 'modules/exports/departmentLevelResult',[
            'studentResults' => $this->studentResult,
            'resultInfo' => $this->resultInfo,
            'tableHeaders' => $this->tableHeader,
            'analysis' => $this->analysis
        ]);
    }
}
