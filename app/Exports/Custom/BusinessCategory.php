<?php

namespace App\Exports\Custom;

use App\Scopes\ActiveProviderScope;
use Illuminate\Contracts\View\View as ViewAlias;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\BeforeExport;

class BusinessCategory implements FromView,ShouldAutoSize
{
    use Exportable;

    private $title;

    public function __construct( $title )
    {

        $this->title = $title;
    }

    public function view(): ViewAlias
    {
        $data = [];
        $rows = [];
        $headers = [
            'Business Category',
            'Total Provider in this Category',
        ];
        $providers = \App\Models\BusinessCategory::all();
        foreach ($providers as $provider) {
            $rows[] = [
                'business_category_name' => $provider->business_category_name,
                'company' => $provider->companies()->withoutGlobalScope(ActiveProviderScope::class)->count()
            ];
        }
        $data['headers'] = $headers;
        $data['data'] = $rows;
//        dd($data);
        return \view('exports.AllProvider', [ 'list' => $data ]);
    }

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

    public function title(): string
    {
        return $this->title;
    }
}
