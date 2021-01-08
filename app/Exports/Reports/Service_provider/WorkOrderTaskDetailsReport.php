<?php

namespace App\Exports\Reports\Service_provider;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class WorkOrderTaskDetailsReport implements FromView,ShouldAutoSize
{
    protected $task_details_list;


    public function __construct($task_details_list)
    {
        $this->task_details_list = $task_details_list;
    }

    public function view(): View
    {

        return view('admin.report.exports.service_provider.work_order_task_details_report', [
            'task_details_list' => $this->task_details_list
        ]);
    }
}
