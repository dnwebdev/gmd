<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendEmail;

class CompanyWithdrawal extends Controller
{
    var $company = 0;
    
    /**
     * __construct
     *
     * @param  mixed $request
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('host');
        $this->middleware('auth')->except('xendit_accept_disbursement');
        //$this->middleware('company');
        
    }

    /**
     * Function initalize get data user
     *
     * @param  mixed $request
     *
     * @return void
     */
    private function initalize(Request $request){
        $user = \Auth::user();

        $this->user = $user->id_user_agen;
        $this->company = $user->id_company;
        
    }

    /**
     * Display a listing of the resource.
     *
     * @param  mixed $request
     *
     * @return void
     */
    public function index(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            return $this->loadData($request);
        }
        
        $this->initalize($request);

        $start_date = date('Y-m-1');
        $end_date = date('Y-m-t');
        //$d_data = \App\Models\WithdrawRequest::where(['id_company'=>$this->company])->orderBy('created_at','desc')->paginate(10);

        $journal_service = app('\App\Services\JournalService');
        $total_balance = json_decode($journal_service->get_company_total_balance($this->company)->getContent());
        $total_balance = $total_balance->data ? $total_balance->data->total_balance : 0;
        if (auth()->user()->company->is_klhk == 1){
            return view('klhk.dashboard.company.withdraw.index',['total_balance'=>$total_balance ]);
        }
        return view('dashboard.company.withdraw.index',['total_balance'=>$total_balance ]);
    }


    public function loadData(Request $request)
    {
        $withdraw = \App\Models\WithdrawRequest::where(['id_company'=> $request->user()->id_company]);

        return \DataTables::of($withdraw)
            ->editColumn('created_at', function ($model) {
                return [
                    'display'   => $model->created_at->format('M d, Y'),
                    'timestamp' => $model->created_at->timestamp
                ];
            })
            ->addColumn('status_text', function ($model) {
                return $model->status_text;
            })
            ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  mixed $request
     *
     * @return void
     */
    public function store(Request $request){
    	$this->initalize($request);
    	if (empty(auth('web')->user()->company->bank)){
            return apiResponse('403', \trans('withdraw_provider.not_rekening'));
        }
        if (empty(auth('web')->user()->email)) {
            return apiResponse('403', \trans('withdraw_provider.not_email'));
        }
    	$rules = [
    	  'amount'=>'required|numeric|min:10000'
        ];
    	$this->validate($request,$rules,[
            'amount.required'=>trans('custom_validation.amount_withdrawal_required')
        ],[
            'amount'=>trans('custom_validation.amount_withdrawal')
        ]);

    	$amount = $request->get('amount');
        if(!empty($amount) && $amount > 0){
            $journal_service = app('\App\Services\JournalService');
        	$total_balance = json_decode($journal_service->get_company_total_balance($this->company)->getContent());
        	$total_balance = $total_balance->data->total_balance;
            $number = microtime(true);
            $number = str_replace('.', '',$number);
            $doc_no = 'DEP'.$this->company.$number;

            if($amount <= $total_balance){
                $bank = \App\Models\CompanyBank::where(['id_company'=>$this->company, 'status'=>1])->orderBy('created_at','desc')->first();
                if(empty($bank)) {
                    return response()->json([
                        'status' => 400,
                        'message' => 'No active bank account'
                    ]);
                } else {
                    $data = [
                        "external_id"=>$doc_no,
                        "bank_code"=>$bank->bank,
                        "account_holder_name"=>$bank->bank_account_name,
                        "account_number"=>$bank->bank_account_number,
                        "description"=>'Withdrawal',
                        "amount"=>$amount,
                    ];
    
                    $xendit = app('\App\Services\XenditService');
                    $service = json_decode($xendit->make_disbursement($data));
                    if ($service->status == 200) {
                        \DB::transaction(function () use($amount, $doc_no) {
                            \App\Models\WithdrawRequest::create([
                                'document_no'=>$doc_no,
                                'id_company'=>$this->company,
                                'id_user'=>$this->user,
                                'amount'=>$amount,
                                'currency'=>'IDR',
                                'status'=>0,
                            ]);

                        $journal = app('App\Services\JournalService');
                
                        $journal->add(['id_company' => $this->company,
                                'journal_code' => $doc_no,
                                'journal_type' => 200,
                                'description' => 'Withdrawal',
                                'currency' => 'IDR',
                                'rate' => 1,
                                'status' => 0,
                                'amount' => -$amount,
                            ]);
                });

                $company = \App\Models\Company::find($this->company);

                $email_data = [
                    "company_name"=> $company->company_name,
                    "external_id"=>$doc_no,
                    "bank_code" => $bank->bank_name,
                    "account_holder_name"=>$bank->bank_account_name,
                    "account_number"=>$bank->bank_account_number,
                    "amount"=>$amount,
                ];

                $email_data_gomodo = [
                    "body"=>$company->company_name." requesting a withdrawal to ".$bank->bank." ".$bank->bank_account_number." with total of IDR ".$amount
                ];

                $template = view('dashboard.company.withdraw.confirmation_mail', $email_data)->render();
                $template_gomodo = view('basic_email', $email_data_gomodo)->render();
                dispatch(new SendEmail('Withdrawal Confirmation #'.$doc_no, $company->email_company, $template));
                dispatch(new SendEmail('Withdrawal Activity #'.$doc_no, "admin@mygomodo.com", $template_gomodo));

                return response()->json([
                                'status' => 200,
                                'message' => \trans('withdraw_provider.withdraw_process'),
                        ]);
                    } else {
                        return response()->json([
                            'status' => 400,
                            'message' => $service->message,
                            ]);
            }
                }
            }

            else{
                return response()->json([
                                'status' => 400,
                                'message' => \trans('withdraw_provider.insufficient_balance'),
                                
                            ]);
            }
        }
        else{
            return response()->json([
                                'status' => 400,
                                'message' => \trans('withdraw_provider.invalid_amount'),
                                
                            ]);
        }
    }

    /**
     * function xendit accept disbursement
     *
     * @return void
     */
    public function xendit_accept_disbursement(){
    	$xendit = app('App\Services\XenditService');
        $res = json_decode($xendit->accept_disbursement());
        $utility = app('\App\Services\UtilityService');
        if($res->status == 200 && $res->message == 'failed'){
            $company = \App\Models\Company::find($res->data->id_company);
            $data = ['amount'=>$res->data->amount, 'status'=>$res->message, 'reason'=>$res->data->reason,'company'=>$company];
            $subject = 'Withdrawal '.$res->data->document_no.' Failed';
            if(!empty($company->email_company)){
                $to = $company->email_company;
                $template = view('dashboard.company.withdraw.mail', $data)->render();
                dispatch(new SendEmail($subject, $to, $template));
            }
            return response()->json([
                'status' => 200,
                'message' => 'Callback Success',
            ]);
    	} else if($res->status == 200 && $res->message == 'completed') {
            $company = \App\Models\Company::find($res->data->id_company);
            $data = ['amount'=>$res->data->amount, 'status'=>$res->message,'company'=>$company];
            $subject = 'Withdrawal '.$res->data->document_no.' Processed';
            if(!empty($company->email_company)){
                $to = $company->email_company;
                $template = view('dashboard.company.withdraw.mail', $data)->render();
                dispatch(new SendEmail($subject, $to, $template));
            }

            return response()->json([
                'status' => 200,
                'message' => 'Callback Success',      
            ]);
        }
    }
}
