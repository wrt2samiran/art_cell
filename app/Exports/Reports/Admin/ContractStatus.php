<?php

namespace App\Exports\Reports\Admin;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ContractStatus implements FromView,ShouldAutoSize
{
    protected $contracts;


    public function __construct($contracts)
    {
        $this->contracts = $contracts;
    }

    public function view(): View
    {

        return view('admin.report.exports.admin.contract_status', [
            'contracts' => $this->contracts
        ]);
    }
}
