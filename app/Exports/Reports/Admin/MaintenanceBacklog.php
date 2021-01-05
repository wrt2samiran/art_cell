<?php

namespace App\Exports\Reports\Admin;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class MaintenanceBacklog implements FromView,ShouldAutoSize
{
    protected $service_dates;


    public function __construct($service_dates)
    {
        $this->service_dates = $service_dates;
    }

    public function view(): View
    {

        return view('admin.report.exports.admin.maintenance_backlog', [
            'service_dates' => $this->service_dates
        ]);
    }
}
