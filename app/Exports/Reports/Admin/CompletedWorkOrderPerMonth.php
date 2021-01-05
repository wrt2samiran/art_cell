<?php

namespace App\Exports\Reports\Admin;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CompletedWorkOrderPerMonth implements FromView,ShouldAutoSize
{
    protected $data;


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
}
