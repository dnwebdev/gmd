<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\AdditionalOrder;
use App\Models\Order;
use App\Models\Product;

class DashboardController extends Controller
{
    public function data()
    {
        $company = auth('api')->user()->company;
        $order = Order::where('id_company', $company->id_company);
        $total = $order->where('status', 1)->count();
        $totalProduct = Product::where('id_company', $company->id_company)->where('booking_type', 'online')->count();
        $lastOrder = Order::with('order_detail')->where('id_company', $company->id_company)->where('booking_type', 'online')->latest()->take(3)->get();
        $journal = app('\App\Services\JournalService');
        $report_total_saldo = json_decode($journal->get_company_total_balance($company->id_company)->getContent());

        $incoming_settlement = Order::whereIdCompany($company->id_company)->where('status', 1)->where('payment_list', '!=', 'Manual Transfer')
            ->whereHas('payment', function ($payment) {
                $payment->where('payment_gateway', '!=', 'Cash On Delivery')->where('status', 'PENDING');
            })->sum('total_amount');
        return apiResponse(200, 'OK', [
            'id' => $company->id_company,
            'total_incoming' => $this->get_value_of_order($company->id_company),
            'total_income' => $this->get_total_paid($company->id_company),
            'count_order' => $total,
            'total_online_paid_transaction' => $this->get_total_paid_online($company->id_company),
            'total_offline_paid_transaction' => $this->get_total_paid_internal($company->id_company),
            'total_saldo' => $report_total_saldo->data->total_balance,
            'count_product' => $totalProduct,
            'incoming_settlement' => $incoming_settlement,
            'last_order' => $lastOrder,
        ]);
    }

    private function get_value_of_order($find)
    {
        $report = \App\Models\Order::where(['id_company' => $find])->whereNotIn('status',
            [5, 6, 7])->selectRaw(" SUM(total_amount - fee_credit_card - fee) as total ")->first();
        return $report->total;
    }

    /**
     * function get total paid online
     *
     * @param mixed $find
     *
     * @return void
     */
    private function get_total_paid_online($id_company)
    {
        $report = \App\Models\Order::where(['id_company' => $id_company, 'status' => 1])->where('payment_list', '!=', 'Manual Transfer')->whereHas('payment',
            function ($payment) {
                $payment->whereNotIn('payment_gateway', ['Cash On Delivery', 'Manual Transfer BCA']);
            })->selectRaw(" SUM(total_amount - fee_credit_card - fee) as total ")->first();
        $asuransi = (int)AdditionalOrder::whereHas('order', function ($o) use ($id_company) {
            $o->where('id_company', $id_company)->where('status', 1)->whereHas('payment',
                function ($payment) {
                    $payment->whereNotIn('payment_gateway', ['Cash On Delivery', 'Manual Transfer BCA']);
                });
        })->where('type', 'insurance')->sum('total');

        return $report->total - $asuransi;
    }

    private function get_total_paid_internal($find)
    {
        $report = \App\Models\Order::where(['id_company' => $find, 'status' => 1])->whereHas('payment', function ($payment) {
            $payment->whereIn('payment_gateway', ['Cash On Delivery', 'Manual Transfer BCA']);
        })->selectRaw(" SUM(total_amount) as total ")->first();
        return $report->total;
    }

    private function get_total_paid($find)
    {
        $id_company = $find;
        $report = \App\Models\Order::where([
            'id_company' => $find,
            'status' => 1
        ])->selectRaw(" SUM(total_amount - fee_credit_card - fee) as total ")->first();
        $asuransi = (int)AdditionalOrder::whereHas('order', function ($o) use ($id_company) {
            $o->where('id_company', $id_company)->where('status', 1)->where('payment_list', '!=', 'Manual Transfer')
                ->whereHas('payment', function ($payment) {
                    $payment->whereNotIn('payment_gateway', ['Cash On Delivery', 'Manual Transfer BCA']);
                });
        })->where('type', 'insurance')->sum('total');

        return $report->total - $asuransi;
    }
}