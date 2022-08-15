<?php

namespace App\Exports\Custom;

use App\Models\Ads;
use App\Models\OrderAds;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PremiumReport implements FromView, ShouldAutoSize
{
    use Exportable;

    public function __construct()
    {
    }

    public function view(): View
    {
        $premium = OrderAds::with('adsOrder')->whereIn('status_payment', ['PAID', 'SETTLED'])->orderBy('created_at', 'asc')->get();
        return view('exports.premium_export', compact('premium'));
    }
}
