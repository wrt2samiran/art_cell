<?php

namespace App\Exports\Reports\Service_provider;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class WorkOrderTaskReport implements FromView,ShouldAutoSize
{
    protected $task_list;


    public function __construct($task_list)
    {
        $this->task_list = $task_list;
    }

    public function view(): View
    {

        return view('admin.report.exports.service_provider.work_order_task_report', [
            'task_lists' => $this->task_list
        ]);
    }
}
