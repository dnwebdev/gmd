<?php

namespace App\Http\Controllers;

use App\Models\ChangeBankRequest;
use App\Models\Company;
use Illuminate\Http\Request;

class AcceptChangeBankRequestController extends Controller
{

    public function __construct()
    {
        $this->middleware('company');
    }

    public function changeBankAccountRequestAction(Request $request)
    {
        $where = [
            'id' => $request->get('u'),
            'token' => $request->get('t')
        ];
        $changeRequest = ChangeBankRequest::where($where)->whereHas('bank_account', function ($cb) use ($request) {
            $cb->where('id_company', $request->get('my_company'));
        })->first();
        if ($changeRequest) {
            if ($request->get('action') == 'approve') {
                try {
                    \DB::beginTransaction();
                    if ($changeRequest->bank_account->bank_account_document) {
                        $image = $changeRequest->bank_account->bank_account_document;
                        $file = public_path() . '/uploads/bank_document/' . $image;
                        \File::delete($file);
                    }
                    $changeRequest->bank_account->update([
                        'bank' => $changeRequest->bank,
                        'bank_account_name' => $changeRequest->bank_account_name,
                        'bank_account_number' => $changeRequest->bank_account_number,
                        'bank_account_document' => $changeRequest->bank_account_document
                    ]);
//                    foreach (ChangeBankRequest::where('id_company_bank', $changeRequest->id_company_bank)->where('id', '!=', $changeRequest->id)->get() as $item) {
//                        if ($item->bank_account_document) {
//                            $image = $item->bank_account->bank_account_document;
//                            $file = public_path() . '/uploads/bank_document/' . $image;
//                            \File::delete($file);
//                        }
//                    }

                    ChangeBankRequest::where('id_company_bank', $changeRequest->id_company_bank)->delete();
                    \DB::commit();
                    $result = [
                        'company' => Company::find($request->get('my_company')),
                        'orderAds' => null,
                        'status' => 'ok',
                        'type' => 'approve',
                        'message' => 'Selamat Akun Rekening Anda sudah Berubah'
                    ];
                    return view('customer.bank.change-bank', $result);
                } catch (\Exception $exception) {
                    \DB::rollBack();
                    $result = [
                        'company' => Company::find($request->get('my_company')),
                        'orderAds' => null,
                        'status' => 'ko',
                        'type' => 'approve',
                        'message' => 'Terjadi Kesalahan dalam mengubah data akun bank Anda'
                    ];
                    return view('customer.bank.change-bank', $result);
                }
            } else {
                try {
                    \DB::beginTransaction();

                    $changeRequest->delete();
                    \DB::commit();
                    $result = [
                        'company' => Company::find($request->get('my_company')),
                        'orderAds' => null,
                        'status' => 'ok',
                        'type' => 'reject',
                        'message' => 'Anda berhasil menolak permintaan untuk mengubah akun rekening Anda'
                    ];
                    return view('customer.bank.change-bank', $result);
                } catch (\Exception $exception) {
                    \DB::rollBack();
                    $result = [
                        'company' => Company::find($request->get('my_company')),
                        'orderAds' => null,
                        'status' => 'ko',
                        'type' => 'reject',
                        'message' => 'Terjadi Kesalahan dalam mengubah data akun bank Anda'
                    ];
                    return view('customer.bank.change-bank', $result);
                }
            }


        }
        $result = [
            'company' => Company::find($request->get('my_company')),
            'orderAds' => null,
            'status' => 'ko',
            'type' => 'general',
            'message' => 'Permintaan Anda tidak dapat kami temukan'
        ];
        return view('customer.bank.change-bank', $result);

    }
}
