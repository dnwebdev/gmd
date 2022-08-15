<?php

namespace App\Exports;

use App\Models\CustomDetail;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\Country;
use App\Models\State;
use App\Models\City;

class CustomDetailExport implements FromView
{
    private $_invoice_no;

    /**
     * Constructor
     * @param string $invoice_no Invoice Number
     * 
     * @return void
     */
    public function __construct(string $invoice_no)
    {
        $this->_invoice_no = $invoice_no;
    }

    public function view(): View
    {
        $invoice_no = $this->_invoice_no;
        $detail = CustomDetail::whereHas('orderDetail', function ($query) use ($invoice_no) {
            return $query->where('invoice_no', $invoice_no);
        })->get();

        $location = $this->lookupLocation($detail);

        return view('exports.custom_detail_order', compact('detail', 'location'));
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
                    });
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
                    });
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
                    });
            }
        }

        return $location;
    }

}
