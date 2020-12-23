<?php

namespace App\Exports\Reports\Admin;

use Maatwebsite\Excel\Concerns\FromCollection;

class WorkOrderRequestedVsCompleted implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        //
    }
}
