<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CompanyWalletController extends Controller
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
        $this->middleware('auth');
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
        // $this->company = $request->get('my_company');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  mixed $request
     *
     * @return void
     */
    public function index(Request $request){

        $this->initalize($request);
        $wallet = \App\Models\CompanyWallet::where('id_company',$this->company)->get();
        $xem = 0;
        $gxp = 0;
        foreach($wallet as $row) {
            $url = 'http://bigalice2.nem.ninja:7890/account/mosaic/owned?address='.$row->address;
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HEADER, 0);	
            $tokens = curl_exec($curl);
            curl_close($curl);
            $tokens = json_decode($tokens, true);
            $xem = $tokens['data'][0]['quantity'];
            if (sizeof($tokens['data'])>1 && $tokens['data'][1]['mosaicId']['name']=='gxp') {
                $gxp = $tokens['data'][1]['quantity'];
            } else {
                $gxp = 0;
            }
        }
        return view('dashboard.company.wallet.index',['wallet'=>$wallet, 'xem'=>$xem, 'gxp'=>$gxp]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  mixed $request
     *
     * @return void
     */
    public function store(Request $request)
    {
        $this->initalize($request);
        $id = null;
        $url = 'http://localhost:3000/nem/account/create';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, 0);	
        $res = curl_exec($curl);
        curl_close($curl);
        $json = json_decode($res, true);
        $data = $json['data'];
        $address = $data['address'];
        $secret = $data['secret'];
        \DB::transaction(function () use($request,$address,&$id) {
            $newwallet = \App\Models\CompanyWallet::create([
                                                    'address'=>$address,
                                                    'id_company'=>$this->company
                                                    ]);
        });
        return json_encode([
            'status'=>200,
            'message'=>'<a href="'.Route('company.wallet.index').'">New wallet address generated</a>',
            'data'=>['url'=>Route('company.wallet.index'), 'secret'=>$secret]]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  mixed $request
     * @param  mixed $wallet
     *
     * @return void
     */
    public function edit(Request $request,\App\Models\Companywallet $wallet)
    {
        $this->initalize($request);
        if($wallet->id_company != $this->company){
            return response()->json([
                                        'status' => 405,
                                        'message' => 'Action not allowed'
                                    ]);
        }
        return view('dashboard.company.wallet.edit',['wallet'=>$wallet]);
    }
}
