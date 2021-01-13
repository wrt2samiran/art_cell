<?php

namespace App\Exports\Reports\Customer;

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

class MaintenanceScheduleReport implements FromView,ShouldAutoSize,WithEvents,WithStyles
{
    protected $service_dates;
    use RegistersEventListeners;
    public function __construct($service_dates)
    {
        $this->service_dates = $service_dates;
    }

    public function view(): View
    {

        return view('admin.report.exports.customer.maintenance_schedule_report', [
            'service_dates' => $this->service_dates
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
