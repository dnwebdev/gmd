<?php

namespace App\Http\Controllers\Backoffice\Premium;

use PDF;
use Carbon\Carbon;
use App\Models\Ads;
use App\Models\OrderAds;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class PremiumCtrl extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        toastr();
        return view('back-office.page.premium.index');
    }

    // Function load datatables premium index
    public function loadData()
    {
        $models = Ads::with(['order_ads','company'])->orderBy('created_at', 'desc')->get();
        return \DataTables::of($models)
            ->addIndexColumn()
            ->addColumn('company', function($model){
                return $model->company->company_name;
            })
            ->addColumn('no_invoice', function($model){
                return $model->order_ads->no_invoice;
            })
//            ->editColumn('date', function ($model){
//                return Carbon::parse($model->start_date)->format('d M Y').' - '.Carbon::parse($model->end_date)->format('d M Y');
//            })
//          ->
            ->addColumn('created_at', function($model){
                return $model->created_at->format('d M Y H:i:s');
            })
            ->addColumn('category_ads', function ($model) {
                return $model->order_ads->category_ads;
            })
            ->addColumn('status', function ($model){
                if ($model->order_ads){
                    return $model->order_ads->status;
                }
                return '-';
            })
            ->addColumn('action', function ($model) {
                // $detail = '<button data-status="' . $model->order_ads->status . '" 
                //     data-id="' . $model->id . '"
                //     data-no_invoice="' .$model->order_ads->no_invoice. '"
                //     data-payment_method="' .$model->order_ads->payment_gateway. '"
                //     data-min_budget="' .number_format($model->min_budget,0). '"
                //     data-total_price="' .number_format($model->order_ads->total_price,0). '"
                // class="btn btn-outline-info btn-sm m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill m-btn--air" id="btn-preview" href=""><i class="fa flaticon-visible"></i></button> ';
                // return $detail;
                $voucherAmount = null;
                $gxp_amount = null;
                if($model->order_ads->voucher_cashback_id){
                    $voucherAmount = $model->order_ads->voucherAds->nominal;
                }elseif($model->order_ads->voucher){
                    $voucherAmount = $model->order_ads->voucher;
                }
                if($model->order_ads->gxp_amount){
                    $gxp_amount = $model->order_ads->gxp_amount;
                }
                if($model->order_ads->status != '0' && $model->order_ads->status != '4' && $model->order_ads->status != '3' && $model->order_ads->status != '2'){
                    $html = '<button data-status="' . $model->order_ads->status . '" data-id="' . $model->id . '"  class="btn btn-outline-info btn-sm m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill m-btn--air" id="btn-edit" href=""><i class="fa flaticon-edit"></i></button>
                    <a href="'.route('admin:premium.premium.detail-premium',['id'=>$model->id]).'"  class="btn btn-outline-info btn-preview btn-sm m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill m-btn--air"><i class="fa flaticon-visible"></i></a>';

//                    <button data-status="' . $model->order_ads->status . '"
//                        data-id="' . $model->id . '"
//                        data-no_invoice="' .$model->order_ads->no_invoice. '"
//                        data-payment_method="' .$model->order_ads->payment_gateway. '"
//                        data-min_budget="' .number_format($model->min_budget,0). '"
//                        data-amount="' .number_format($model->order_ads->amount,0). '"
//                        data-service_fee="' .number_format($model->service_fee,0). '"
//                        data-voucher_amount="' .number_format($voucherAmount,0). '"
//                        data-gxp_amount="' .number_format($gxp_amount,0). '"
//                        data-total_price="' .number_format($model->order_ads->total_price,0). '"
//                    class="btn btn-outline-info btn-sm m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill m-btn--air" id="btn-preview" href=""><i class="fa flaticon-visible"></i></button>
                    return $html;
                }else{
                    $html = '<a href="'.route('admin:premium.premium.detail-premium',['id'=>$model->id]).'"  class="btn btn-outline-info btn-preview btn-sm m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill m-btn--air"><i class="fa flaticon-visible"></i></a> ';
                    return $html;

                }
            })
            ->make(true);
    }

    // Function update status premium
    public function update(Request $request)
    {
        $ads = Ads::find($request->id);
        $to = "store@mygomodo.com";

        if ($ads) {
            $ads->order_ads()->update([
                'status'=>$request->input('status')
                ]);

            $data = [
                'url' => $ads->url,
                'no_invoice' => $ads->order_ads->no_invoice,
                'company_name' => $ads->company->company_name,
                'email_company' => $ads->company->email_company,
                'first_name' => $ads->company->agent->first_name,
                'date_active' => date('d M Y', strtotime($ads->start_date)).' - '.date('d M Y', strtotime($ads->end_date)). 'Hari',
                'start_date' => date('d M Y', strtotime($ads->start_date)),
                'end_date' => date('d M Y', strtotime($ads->end_date)),
                'total_price' => $ads->order_ads->total_price,
                'updated_at' => $ads->order_ads->updated_at->format('d-m-Y'),
                'time_updated_at' => $ads->order_ads->updated_at->format('h:i a'),
                'category_ads' => $ads->order_ads->category_ads,
            ];
            
            if ($ads->order_ads->status == 1) {
                // Email to Provider status paid
                // Mail::send('back-office.page.premium.email.mail_paid', $data, function($message) use ($data, $to){
                //     $message->to($data['email_company'])->subject('Invoice Order '. $data['category_ads'] .': Status Paid');
                //     $message->from($to, 'Admin Gomodo');
                // });
            } elseif($ads->order_ads->status == 2){
                // Email to Provider status aktif
                Mail::send('back-office.page.premium.email.mail_active', $data, function($message) use ($data, $to){
                    $message->to($data['email_company'])->subject('Invoice Order '. $data['category_ads'] .': Status Active');
                    $message->from($to, 'Admin Gomodo');
                });
            } elseif($ads->order_ads->status == 3){
                // Email to Provider status inactive
                Mail::send('back-office.page.premium.email.mail_inactive', $data, function($message) use ($data, $to){
                    $message->to($data['email_company'])->subject('Invoice Order '. $data['category_ads'] .': Status Inactive - Extend Now');
                    $message->from($to, 'Admin Gomodo');
                });
            }

            
            msg('Premium Updated!');
            return redirect()->back();
        }
        msg('Premium not found!', 2);
        return redirect()->back();
    }

    public function detailPremium($id)
    {
        $find = Ads::with([
            'adsCity',
            'adsCity.state',
            'adsCity.state.country',
            'businessCategory',
            'order_ads'
        ])->find($id);
        return view('back-office.page.premium.detail', compact('find'));
    }

    public function download($id)
    {
        $tes = Ads::find($id);
//        $filepath = public_path('storage/image-ads/'.$tes->document_ads);
        return response()->download(public_path('storage/image-ads/'.$tes->document_ads));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    //  
    public function destroy($id)
    {
        // $lang = TagProduct::find($request->id);
        // if ($lang) {
        //     $lang->delete();
        //     msg('Tag Product Deleted!');
        //     return redirect()->back();
        // }
        // msg('Tag Product not found!', 2);
        // return redirect()->back();
    }
}
