<?php

namespace App\Exports\Reports\Customer;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class WorkOrderReport implements FromView,ShouldAutoSize
{
    protected $work_orders;


    public function __construct($work_orders)
    {
        $this->work_orders = $work_orders;
    }

    public function view(): View
    {

        return view('admin.report.exports.customer.work_order_report', [
            'work_orders' => $this->work_orders
        ]);
    }
}
