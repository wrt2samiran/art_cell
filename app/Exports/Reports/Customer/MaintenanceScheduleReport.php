<?php

namespace App\Exports\Reports\Customer;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class MaintenanceScheduleReport implements FromView,ShouldAutoSize
{
    protected $service_dates;

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
}
