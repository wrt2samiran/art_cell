<?php

namespace App\Exports\Reports\Admin;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class UpcomingSchedulePerWeek implements FromView,ShouldAutoSize
{
    protected $upcoming_weekly_services;


    public function __construct($upcoming_weekly_services)
    {
        $this->upcoming_weekly_services = $upcoming_weekly_services;
    }

    public function view(): View
    {
        return view('admin.report.exports.admin.upcoming_weekly_schedule_maintenance', [
            'upcoming_weekly_services' => $this->upcoming_weekly_services
        ]);
    }
}
