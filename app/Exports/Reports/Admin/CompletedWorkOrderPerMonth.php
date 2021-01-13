<?php

namespace App\Exports\Reports\Admin;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\BeforeWriting;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Events\AfterSheet;

class CompletedWorkOrderPerMonth implements FromView,ShouldAutoSize,WithEvents,WithStyles
{
    protected $data;
    use RegistersEventListeners;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {

        return view('admin.report.exports.admin.completed_wo_per_month', [
            'data' => $this->data
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            4    => [
             'font' => ['bold' => true],
            ],

        ];
    }

    public static function afterSheet(AfterSheet $event)
    {
            // $event->sheet->getDelegate()->getStyle('A1:N1')
            // ->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('ffff15');

            //$event->sheet->mergeCells('A1:B1');
    }
}
