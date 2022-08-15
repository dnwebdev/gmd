<?php

namespace App\Http\Controllers\Backoffice\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Models\Order;
use App\Models\Company;
use DB;

class DashboardCtrl extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        if (!Auth::guard('admin')->check()) {
            return redirect(route('admin:login'));
        }
        $statistic = [];
        $top_providers = [];
        if (auth('admin')->user()->is_klhk) {
            $statistic = collect([
                ['today', 1],
                ['day', 14],
                ['day', 30],
                ['day', 90]
            ])->mapWithKeys(function ($item) {
                return [implode('-', array_reverse($item)) => $this->statistics(...$item)];
            });

            $top_providers = $this->topProvider($request, $request->input('top-provider', 30));
        }

        toastr();
        return viewKlhk('back-office.page.dashboard.index', [
            'new-backoffice.dashboard.index', compact('statistic', 'top_providers')
        ]);
    }

    public function statistics($range = 'today', $n = 1)
    {
        return [
            'total_order' => number_format(
                Order::dateRange($range, $n)
                    ->wherePaid()
                    ->whereHas('company', function ($query) {
                        return $query->where('is_klhk', true);
                    })
                    ->sum('total_amount'),
                0, '', '.'
            ),
            'total_order_offline' => number_format(
                Order::dateRange($range, $n)
                    ->whereCod()
                    ->wherePaid()
                    ->whereHas('company', function ($query) {
                        return $query->where('is_klhk', true);
                    })
                    ->sum('total_amount'),
                0, '', '.'
            ),
            'total_order_online' => number_format(
                Order::dateRange($range, $n)
                    ->whereCod(false)
                    ->wherePaid()
                    ->whereHas('company', function ($query) {
                        return $query->where('is_klhk', true);
                    })
                    ->sum('total_amount'),
                0, '', '.'
            )
        ];
    }

    public function topProvider(Request $request, $day = 30)
    {
        $top = Company::select('id_company', 'company_name', 'logo', 'domain_memoria')
            ->withCount(['order as total_order' => function ($query) use ($day) {
                return $query->select(DB::raw('IFNULL(SUM(total_amount), 0) as orderoffline'))->wherePaid()->dateRange('day', $day);
            }])
            ->withCount(['order as order_online' => function ($query) use ($day) {
                return $query->select(DB::raw('IFNULL(SUM(total_amount), 0) as orderonline'))->wherePaid()->whereCod(false)->dateRange('day', $day);
            }])
            ->withCount(['order as order_offline' => function ($query) use ($day) {
                return $query->select(DB::raw('IFNULL(SUM(total_amount), 0) as orderoffline'))->wherePaid()->whereCod()->dateRange('day', $day);
            }])
            ->where('is_klhk', true)
            ->orderBy('total_order', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                return [
                    'id'            => $item->id_company,
                    'name'          => $item->company_name,
                    'logo'          => $item->logo,
                    'total_order'   => $item->total_order,
                    'order_online'  => number_format($item->order_online, 0, '', '.'),
                    'order_offline' => number_format($item->order_offline, 0, '', '.'),
                    'url'           => 'http'.(request()->secure() ? 's' : '').'://'.$item->domain_memoria 
                ];
            });

        return $top;
    }
}
