<?php

namespace App\Exports\Custom;


use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Concerns\Exportable;

class ReportInvestor implements WithMultipleSheets
{
    use Exportable;
    /**
     * @var array
     */
    private $lists;

    public function __construct( array $lists )
    {
        $this->lists = $lists;
    }
    public function sheets(): array
    {
        $sheets = [];

        foreach ($this->lists as $list) {
            $sheets[] = new ReportInvestorMonthly($list);
        }

        return $sheets;
    }

}
