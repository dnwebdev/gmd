<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use App\Models\CustomSchema;
use App\Models\Product;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\CustomDetail;

class CompanyProductExport implements FromView, ShouldAutoSize, WithEvents
{
    use Exportable;

    private $_id;

    private $_paid_only = false;

    public function __construct(int $id, $paid_only = false)
    {
        $this->_id = $id;
        $this->_paid_only = $paid_only;
    }

    public function view(): View
    {
        $id = $this->_id;
        $customSchema = CustomSchema::where('product_id', $id)
            ->orderBy('id', 'asc')
            ->get();

        $allParticipant = $customSchema->map(function ($item) {
            return $item->fill_type != 'customer';
        });

        $orders = Order::with([
                'order_detail.product',
                'customer_info',
                'payment',
                'order_detail.customDetail' => function ($query) use ($customSchema) {
                    return $query->whereIn('custom_schema_id', $customSchema->pluck('id')->toArray())
                        ->orderBy('custom_schema_id', 'asc')
                        ->orderBy('participant', 'asc');
                }
            ])
            ->whereHas('order_detail', function ($query) use ($id) {
                return $query->where('id_product', $id);
            })
            ->where([
                'id_company'    => auth('web')->user()->id_company,
                'booking_type'  => 'online'
            ])
            ->when($this->_paid_only, function ($query) {
                return $query->wherePaid(true);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $detail = CustomDetail::whereIn('type_custom', ['city', 'state', 'country'])
            ->whereHas('orderDetail', function ($query) use ($id) {
                return $query->where('id_product', $id);
            })->get();
        $location = $this->lookupLocation($detail);

        return view('exports.company_product', compact('orders', 'customSchema', 'location', 'allParticipant'));
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'A1:I1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);
                $event->sheet->getRowDimension('1')->setRowHeight(20);

                $style = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ]
                ];

                $event->sheet->getDelegate()->getStyle('A1:'.$event->sheet->getHighestColumn().$event->sheet->getHighestRow())
                    ->applyFromArray($style);
            },
        ];
    }

    protected function lookupLocation($model)
    {
        // Jika custom info tidak kosong
        // Kita lookup di awal untuk menghindari N+1
        $location = [];
        if ($model->isNotEmpty()) {
            // Jika type negara tidak kosong maka lookup data negara
            if ($model->where('type_custom', 'country')->count() > 0) {
                $countries = $model->where('type_custom', 'country')->pluck('value')->toArray();
                $location['country'] = Country::select('id_country', 'country_name')
                    ->whereIn('id_country', $countries)
                    ->get()
                    ->mapWithKeys(function ($item) {
                        return [$item->id_country => $item->country_name];
                    })
                    ->toArray();
            }

            // Jika type state tidak kosong maka lookup dulu
            if ($model->where('type_custom', 'state')->count() > 0) {
                $states = $model->where('type_custom', 'state')->pluck('value')->toArray();
                $location['state'] = State::select('id_state', 'state_name', 'id_country')
                    ->whereIn('id_state', $states)
                    ->with('country:id_country,country_name')
                    ->get()
                    ->mapWithKeys(function ($item) {
                        return [
                            $item->id_state => $item->state_name.', '.$item->country->country_name
                        ];
                    })
                    ->toArray();
            }

            if ($model->where('type_custom', 'city')->count() > 0) {
                $cities = $model->where('type_custom', 'city')->pluck('value')->toArray();
                $location['city'] = City::select('id_city', 'city_name', 'id_state')
                    ->whereIn('id_city', $cities)
                    ->with([
                        'state:id_state,state_name,id_country',
                        'state.country:id_country,country_name'
                    ])
                    ->get()
                    ->mapWithKeys(function ($item) {
                        return [
                            $item->id_city => $item->city_name.', '.$item->state->state_name.', '.$item->state->country->country_name
                        ];
                    })
                    ->toArray();
            }
        }

        return $location;
    }
}
