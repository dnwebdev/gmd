<?php

namespace App\Exports\Custom;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SingleReport implements FromView,WithTitle,ShouldAutoSize
{
    use Exportable;

    private $data;
    private $title;

    public function __construct( $data, $title )
    {

        $this->data = $data;
        $this->title = $title;
    }

    public function view(): View
    {
        return \view('exports.singleReport', [ 'list' => $this->data ]);
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
        return  $this->title;
    }
}
