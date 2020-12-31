<?php

namespace App\Exports\Reports\Admin;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class WorkOrder implements FromView,ShouldAutoSize
{
    protected $work_orders;


    public function __construct($work_orders)
    {
        $this->work_orders = $work_orders;
    }

    public function view(): View
    {

        return view('admin.report.exports.admin.work_order', [
            'work_orders' => $this->work_orders
        ]);
    }
}
