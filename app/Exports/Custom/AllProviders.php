<?php

namespace App\Exports\Custom;

use App\Models\Company;
use App\Scopes\ActiveProviderScope;
use Illuminate\Contracts\View\View as ViewAlias;
use Illuminate\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\BeforeExport;

class AllProviders implements FromView, ShouldAutoSize
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
            'Company Name',
            'Domain',
            'Business Categories'
        ];
        $providers = Company::withoutGlobalScope(ActiveProviderScope::class)->get();
        foreach ($providers as $provider) {
            $rows[] = [
                'company_name' => $provider->company_name,
                'domain' => $provider->domain_memoria,
                'business_category' => $provider->categories->implode('business_category_name',', ')
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
