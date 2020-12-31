<?php

namespace App\Exports\Reports\Admin;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
class UpcomingScheduleMaintenance implements FromView,ShouldAutoSize
{
    protected $upcoming_service_dates;


    public function __construct($upcoming_service_dates)
    {
        $this->upcoming_service_dates = $upcoming_service_dates;
    }

    public function view(): View
    {
        return view('admin.report.exports.admin.upcoming_schedule_maintenance', [
            'upcoming_service_dates' => $this->upcoming_service_dates
        ]);
    }

    
}
