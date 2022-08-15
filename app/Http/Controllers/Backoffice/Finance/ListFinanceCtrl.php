<?php

namespace App\Http\Controllers\Backoffice\Finance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Finance;
use App\Models\VerificationFinance;

class ListFinanceCtrl extends Controller
{
    public function index()
    {
        toastr();
        return view('back-office.finance.index');
    }

    public function loadData()
    {
        $finance = Finance::all();
        return \DataTables::of($finance)
            ->addIndexColumn()
            ->editColumn('amount', function ($model){
                return format_priceID($model->amount);
            })
            ->editColumn('created_at', function($model) {
                return $model->created_at->format('d M Y h:i:s');
            })
            ->editColumn('company_name', function($model) {
                return $model->company->company_name;
            })
            ->editColumn('status', function ($model){
                switch ($model->status){
                    case 'need_approval':
                        $class = 'badge badge-warning badge-pill pl-3 pr-3 pt-2 pb-2';
                        break;
                    case 'approved':
                        $class = 'badge badge-success badge-pill pl-3 pr-3 pt-2 pb-2';
                        break;
                    case 'rejected':
                        $class = 'badge badge-danger badge-pill pl-3 pr-3 pt-2 pb-2';
                        break;
                    default:
                        $class = 'badge badge-secondary badge-pill p-2';
                        break;
                }
                return '<span class="'.$class.'">'.$model->status.'</span>';
            })
            ->addColumn('action', function ($model) {
                $html = '<a href="' . route('admin:master.finance.detail', ['id' => $model->id]) . '" data-name="' . $model->company->company_name . '" data-id="' . $model->id . '"  class="btn btn-outline-info btn-preview btn-sm m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill m-btn--air"><i class="fa flaticon-visible"></i></a>';
                return $html;
            })
            ->rawColumns(['action','status'])
            ->make(true);
    }

    public function detail($id)
    {
        if (!$finance = Finance::find($id)){
            msg('Finance Not Found', 2);
            return redirect()->route('admin:master.finance.index');
        }
        return view('back-office.finance.detail', compact('finance'));
    }

    public function downloadAllFile($id)
    {
        $zip = new \ZipArchive;
        $temp_file = tempnam("/tmp", "zip");
        $finance = Finance::find($id);
        $download = VerificationFinance::where('finance_id', $finance->id)->first();

        if ($finance->company->ownership_status == 'corporate') {
            $data = [
                'ktp' => $download->ktp,
                'npwp' => $download->npwp,
                'siup' => $download->siup,
                'founding_deed' => $download->founding_deed,
                'change_certificate' => $download->change_certificate,
                'sk_menteri' => $download->sk_menteri,
                'company_signatures' => $download->company_signatures,
                'report_statement' => $download->report_statement,
                'document_bank' => $download->document_bank,
            ];
        } else {
            $data = [
                'ktp' => $download->ktp,
                'npwp' => $download->npwp,
                'document_bank' => $download->document_bank,
                'ktp_couples' => $download->ktp_couples,
                'family_card' => $download->family_card,
            ];
        }

        $zip->open($temp_file, \ZipArchive::CREATE);

        foreach ($data as $key => $value) {
            if (empty($value)) continue;
            $file = public_path($value);
            $ext = pathinfo($file, PATHINFO_EXTENSION);
            $new_name = 'Finance - '. $finance->company->company_name . ' - '. $key;
            $new_name .= '.' . $ext;
            $zip->addFile($file, $new_name);
        }

        $zip->close();

        return response()->download($temp_file, 'Finance - ' . $finance->company->company_name . '.zip')->deleteFileAfterSend(true);
    }

    public function downloadPdf($id)
    {
        $finance = Finance::find($id);
        $data_provider = ['finance' => $finance];
        $pdf = \PDF::loadView('mail.finance.data-provider', $data_provider)->setPaper('a4');
        return $pdf->download('Data Provider : '. $finance->company->company_name .'.pdf');
    }
}
