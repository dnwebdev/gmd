<?php

namespace App\Exports\Custom;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ReportInvestorMonthly implements FromView,WithTitle,ShouldAutoSize
{
    use Exportable;
    /**
     * @var array
     */
    private $list;

    public function __construct( $list )
    {
        $this->list = $list;
    }
    public function view(): View
    {
        return \view('exports.investorMonthly', [ 'list' => $this->list ]);
    }


    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            // Handle by a closure.
            BeforeExport::class => function ( BeforeExport $event ) {
                $event->writer->getProperties()->setCreator('Karisma Prabakuncara');
            },
//            AfterSheet::class=>function(AfterSheet $sheet){
//                $sheet->a
//            }
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Month ' . $this->list->title;
    }
}
