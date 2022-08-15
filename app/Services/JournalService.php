<?php

namespace App\Services;

use App\Models\AdditionalOrder;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class JournalService
{

    /**
     * process function add journal
     *
     * @param  mixed $data
     *
     * @return void
     */
    public function add($data)
    {

        \DB::transaction(function () use ($data) {
            $debet = 0;
            $credit = 0;
            if ($data['amount'] > 0) {
                $credit = $data['amount'];
            } else {
                $debet = $data['amount'];
            }


            $d = ['journal_code' => $data['journal_code'],
                'journal_type' => $data['journal_type'],
                'description' => $data['description'],
                'currency' => $data['currency'],
                'rate' => $data['rate'],
                'debet' => abs($debet),
                'credit' => abs($credit),
                'status' => isset($data['status']) ? $data['status'] : 1,
                'id_company' => $data['id_company'],
            ];


            $d_journal = [$d];

            $reverse = $d;
            $deb = $reverse['debet'];

            $reverse['id_company'] = 0;
            $reverse['debet'] = $reverse['credit'];
            $reverse['credit'] = $deb;

            if ($reverse['journal_type'] == 100) {
                $reverse['journal_type'] = 101;
            } else if ($reverse['journal_type'] == 200) {
                $reverse['journal_type'] = 201;
            } else if ($reverse['journal_type'] == 900) {
                $reverse['journal_type'] = 901;
            }

            $reverse['description'] = 'Reverse - ' . $reverse['description'];

            array_push($d_journal, $reverse);

            $journal = \App\Models\Journal::insert($d_journal);
        });

    }

    /**
     * process function get company total balance
     *
     * @param  mixed $id_company
     *
     * @return void
     */
    public function get_company_total_balance($id_company)
    {
        $total_balance = \App\Models\Order::where(['id_company' => $id_company, 'status' => 1])->where('payment_list', '!=', 'Manual Transfer')->whereHas('payment', function ($q) {
            $q->where(['status' => 'PAID'])->where('payment_gateway','!=','Cash On Delivery');
        })->selectRaw('SUM(total_amount - fee_credit_card - fee) as total ')->first();

        $totalAsuransi = (int)AdditionalOrder::whereHas('order', function ($o) use ($id_company){
            $o->where('id_company',$id_company)->where('status',1)->whereHas('payment',
                function ($payment) {
                    $payment->where(['status' => 'PAID'])->where('payment_gateway', '!=', 'Cash On Delivery');
                });
        })->where('type','insurance')->sum('total');

        $total_reimbursement = \App\Models\Order::where(['id_company' => $id_company, 'status' => 1])->where('payment_list', '!=', 'Manual Transfer')->whereHas('payment', function ($q) {
            $q->where(['status' => 'PAID'])->where('payment_gateway','!=','Cash On Delivery');
        })->whereHas('voucherGomodo')->where('reimbursement',1)->sum('voucher_amount');

        $withdraw_request = \App\Models\WithdrawRequest::where(['id_company' => $id_company])->whereIn('status', [0, 1])->selectRaw('sum(amount) as total')->first();
        $tot = $total_balance->total - $withdraw_request->total + $total_reimbursement - $totalAsuransi;
        return response()->json([
            'status' => 200,
            'message' => 'ok',
            'data' => [
                'total_balance' => $tot,
                'total_reimbursement'=>$total_reimbursement
            ],

        ]);
    }


    /**
     * process function get company total balance all
     *
     * @param  mixed $id_company
     *
     * @return void
     */
    public function get_company_total_balance_all($id_company)
    {
        $total_balance = \App\Models\Journal::where(['id_company' => $id_company])
            ->select(\DB::raw('SUM((credit-debet)*rate) AS total'))->first();


        return response()->json([
            'status' => 200,
            'message' => 'ok',
            'data' => ['total_balance' => $total_balance->total],

        ]);
    }

}

