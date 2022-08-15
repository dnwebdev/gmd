<?php

namespace App\Exports\Custom;


use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class CustomReport implements FromView
{
    use Exportable;
    private $raw;
    private $headers=[];

    public function __construct(array $headers, $raw)
    {
        $this->raw = $raw;
        $this->headers = $headers;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        $data['results'] = \DB::select(\DB::raw($this->raw));
        $data['headers'] = $this->headers;

        return view('exports.custom',$data);
    }
}
