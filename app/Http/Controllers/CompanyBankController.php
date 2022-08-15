<?php

namespace App\Http\Controllers;

use App\Mail\Bank\BankAccountChangeRequestMail;
use App\Models\Company;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendEmail;
use App\Models\CompanyBank;
use File;
use Image;
use Kayiz\Woowa;

class CompanyBankController extends Controller
{
    var $company = 0;

    /**
     * __construct
     *
     * @param mixed $request
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
     * @param mixed $request
     *
     * @return void
     */
    private function initalize(Request $request)
    {
        $user = \Auth::user();
        $this->user = $user->id_user_agen;
        $this->company = $user->id_company;
        // $this->company = $request->get('my_company');
    }

    /**
     * Display a listing of the resource.
     *
     * @param mixed $request
     *
     * @return void
     */
    public function index(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            return $this->loadData($request);
        }

        $this->initalize($request);

        $bank_count = CompanyBank::where('id_company', $this->company)->count();
        if (auth()->user()->company->is_klhk == 1) {
            return view('klhk.dashboard.company.bank.index', compact('bank_count'));
        }
        return view('dashboard.company.bank.index', compact('bank_count'));
    }

    public function loadData(Request $request)
    {
        $bank = CompanyBank::where('id_company', $request->user()->id_company)
            ->withCount('request_changes')
            ->orderBy('created_at', 'desc');

        return \DataTables::of($bank)
            ->editColumn('bank', function ($model) {
                return CompanyBank::$banks[$model->bank] ?? '-';
            })
            ->addColumn('action', function ($model) {
                return '<a href="' . route('company.bank.edit', $model->id) . '">' . trans('bank_provider.view') . '</a>';
            })
            ->editColumn('request_changes_count', function ($model) {
                return $model->request_changes_count > 0 ? $model->request_changes_count : '';
            })
            ->make(true);
    }


    public function create(Request $request)
    {
        $this->initalize($request);
        $bank = \App\Models\CompanyBank::where('id_company', $this->company)->get();
        if ($bank->count() < 1) {
            if (auth()->user()->company->is_klhk == 1) {
                return view('klhk.dashboard.company.bank.create', compact('bank'));
            }
            return view('dashboard.company.bank.create', compact('bank'));
        }
        return redirect()->back();
    }


    public function store(\App\Http\Requests\BankFormRequest $request)
    {
        $this->initalize($request);
        $id = null;
        if ($request->hasFile('bank_account_document')) {
            $path = public_path('uploads/bank_document');
            $image_name = pathinfo($request->file('bank_account_document')->hashName(), PATHINFO_FILENAME) . '.jpg';
            // Buat folder jika tidak ada
            File::isDirectory($path) or File::makeDirectory($path, 0777, true, true);

            $rotate = (int)$request->input('crop-bank_account_document.rotate');
            $img = Image::make($request->file('bank_account_document'));
            if ($rotate !== 0):
                $img->rotate(360 - $rotate);
            endif;

            $img->crop((int)$request->input('crop-bank_account_document.width'),
                (int)$request->input('crop-bank_account_document.height'), (int)$request->input('crop-bank_account_document.x'), (int)$request->input('crop-bank_account_document.y'))
                ->resize(800, 450, function ($constraint) {
                    return $constraint->aspectRatio();
                })
                ->encode('jpeg')
                ->save($path . '/' . $image_name)
                ->destroy();
        } else {
            $image_name = null;
        }

        \DB::transaction(function () use ($request, &$id, $image_name) {
            $newbank = \App\Models\CompanyBank::create([
                'bank' => $request->get('bank'),
                'bank_account_name' => $request->get('bank_account_name'),
                'bank_account_number' => $request->get('bank_account_number'),
                'status' => 1,
                'id_company' => $this->company,
                'bank_account_document' => $image_name,
            ]);

            $id = $newbank->id;

        });

        $company = \App\Models\Company::find($this->company);

        $name = $company->company_name;
        $email = $company->email_company;

        $message = 'Bank: ' . $request->get('bank') . '. Bank Account Name: ' . $request->get('bank_account_name') . '. Bank Account Number: ' . $request->get('bank_account_number') . '. Bank Doc Filename: ' . $image_name;

        $subject = "Bank Account Verification Request";
        $to = "admin@mygomodo.com";

        $bankName = null;
        if ($request->get('bank') === 'BCA') {
            $bankName = 'Bank Central Asia (BCA)';
        } elseif ($request->get('bank') === 'DANAMON') {
            $bankName = 'Bank Danamon';
        } elseif ($request->get('bank') === 'ARTHA') {
            $bankName = 'Bank Artha Graha International';
        } elseif ($request->get('bank') === 'ANZ') {
            $bankName = 'Bank ANZ Indonesia';
        } elseif ($request->get('bank') === 'BJB') {
            $bankName = 'Bank BJB';
        } elseif ($request->get('bank') === 'BJB_SYR') {
            $bankName = 'Bank BJB Syariah';
        } elseif ($request->get('bank') === 'PERMATA') {
            $bankName = 'Bank Permata';
        } elseif ($request->get('bank') === 'PANIN') {
            $bankName = 'Bank Panin';
        } elseif ($request->get('bank') === 'CAPITAL') {
            $bankName = 'Bank Capital Indonesia';
        } elseif ($request->get('bank') === 'ARTOS') {
            $bankName = 'Bank Artos Indonesia';
        } elseif ($request->get('bank') === 'BUMI_ARTA') {
            $bankName = 'Bank Bumi Arta';
        } elseif ($request->get('bank') === 'BNI_SYR') {
            $bankName = 'Bank BNI Syariah';
        } elseif ($request->get('bank') === 'BUKOPIN') {
            $bankName = 'Bank Bukopin';
        } elseif ($request->get('bank') === 'AGRONIAGA') {
            $bankName = 'Bank BRI Agroniaga';
        } elseif ($request->get('bank') === 'MANDIRI') {
            $bankName = 'Bank Mandiri';
        } elseif ($request->get('bank') === 'AGRIS') {
            $bankName = 'Bank Agris';
        } elseif ($request->get('bank') === 'CIMB') {
            $bankName = 'Bank CIMB Niaga';
        } elseif ($request->get('bank') === 'SINARMAS') {
            $bankName = 'Bank Sinarmas';
        } elseif ($request->get('bank') === 'BANGKOK') {
            $bankName = 'Bangkok Bank';
        } elseif ($request->get('bank') === 'BISNIS_INTERNASIONAL') {
            $bankName = 'Bank Bisnis Internasional';
        } elseif ($request->get('bank') === 'ANGLOMAS') {
            $bankName = 'Anglomas International Bank';
        } elseif ($request->get('bank') === 'ANDARA') {
            $bankName = 'Bank Andara';
        } elseif ($request->get('bank') === 'BNP_PARIBAS') {
            $bankName = 'Bank BNP Paribas';
        } elseif ($request->get('bank') === 'COMMONWEALTH') {
            $bankName = 'Bank Commonwealth';
        } elseif ($request->get('bank') === 'BCA_SYR') {
            $bankName = 'Bank Central Asia (BCA) Syariah';
        } elseif ($request->get('bank') === 'DANAMON_UUS') {
            $bankName = 'Bank Danamon UUS';
        } elseif ($request->get('bank') === 'INA_PERDANA') {
            $bankName = 'Bank Ina Perdania';
        } elseif ($request->get('bank') === 'DKI') {
            $bankName = 'Bank DKI';
        } elseif ($request->get('bank') === 'GANESHA') {
            $bankName = 'Bank Ganesha';
        } elseif ($request->get('bank') === 'CHINATRUST') {
            $bankName = 'Bank Chinatrust Indonesia';
        } elseif ($request->get('bank') === 'HANA') {
            $bankName = 'Bank Hana';
        } elseif ($request->get('bank') === 'DINAR_INDONESIA') {
            $bankName = 'Bank Dinar Indonesia';
        } elseif ($request->get('bank') === 'CIMB_UUS') {
            $bankName = 'Bank CIMB Niaga UUS';
        } elseif ($request->get('bank') === 'DKI_UUS') {
            $bankName = 'Bank DKI UUS';
        } elseif ($request->get('bank') === 'FAMA') {
            $bankName = 'Bank Fama International';
        } elseif ($request->get('bank') === 'HIMPUNAN_SAUDARA') {
            $bankName = 'Bank Himpunan Saudara 1906';
        } elseif ($request->get('bank') === 'ICBC') {
            $bankName = 'Bank ICBC Indonesia';
        } elseif ($request->get('bank') === 'HARDA_INTERNASIONAL') {
            $bankName = 'Bank Harda Internasional';
        } elseif ($request->get('bank') === 'DBS') {
            $bankName = 'Bank DBS Indonesia';
        } elseif ($request->get('bank') === 'INDEX_SELINDO') {
            $bankName = 'Bank Index Selindo';
        } elseif ($request->get('bank') === 'PANIN_SYR') {
            $bankName = 'Bank Panin Syariah';
        } elseif ($request->get('bank') === 'JAWA_TENGAH_UUS') {
            $bankName = 'BPD Jawa Tengah UUS';
        } elseif ($request->get('bank') === 'KALIMANTAN_TIMUR_UUS') {
            $bankName = 'BPD Kalimantan Timur UUS';
        } elseif ($request->get('bank') === 'BTN_UUS') {
            $bankName = 'Bank Tabungan Negara (BTN) UUS';
        } elseif ($request->get('bank') === 'ACEH_UUS') {
            $bankName = 'BPD Aceh UUS';
        } elseif ($request->get('bank') === 'KALIMANTAN_BARAT_UUS') {
            $bankName = 'BPD Kalimantan Barat UUS';
        } elseif ($request->get('bank') === 'JASA_JAKARTA') {
            $bankName = 'Bank Jasa Jakarta';
        } elseif ($request->get('bank') === 'DAERAH_ISTIMEWA') {
            $bankName = 'BPD Daerah Istimewa Yogyakarta (DIY)';
        } elseif ($request->get('bank') === 'KALIMANTAN_BARAT') {
            $bankName = 'BPD Kalimantan Barat';
        } elseif ($request->get('bank') === 'MASPION') {
            $bankName = 'Bank Maspion Indonesia';
        } elseif ($request->get('bank') === 'MAYAPADA') {
            $bankName = 'Bank Mayapada International';
        } elseif ($request->get('bank') === 'BRI_SYR') {
            $bankName = 'Bank Syariah BRI';
        } elseif ($request->get('bank') === 'TABUNGAN_PENSIUNAN_NASIONAL') {
            $bankName = 'Bank Tabungan Pensiunan Nasional';
        } elseif ($request->get('bank') === 'VICTORIA_INTERNASIONAL') {
            $bankName = 'Bank Victoria Internasional';
        } elseif ($request->get('bank') === 'BALI') {
            $bankName = 'BPD Bali';
        } elseif ($request->get('bank') === 'JAWA_TENGAH') {
            $bankName = 'BPD Jawa Tengah';
        } elseif ($request->get('bank') === 'KALIMANTAN_SELATAN') {
            $bankName = 'BPD Kalimantan Selatan';
        } elseif ($request->get('bank') === 'MAYBANK_SYR') {
            $bankName = 'Bank Maybank Syariah Indonesia';
        } elseif ($request->get('bank') === 'SAHABAT_SAMPOERNA') {
            $bankName = 'Bank Sahabat Sampoerna';
        } elseif ($request->get('bank') === 'KALIMANTAN_SELATAN_UUS') {
            $bankName = 'BPD Kalimantan Selatan UUS';
        } elseif ($request->get('bank') === 'KALIMANTAN_TENGAH') {
            $bankName = 'BPD Kalimantan Tengah';
        } elseif ($request->get('bank') === 'MUAMALAT') {
            $bankName = 'Bank Muamalat Indonesia';
        } elseif ($request->get('bank') === 'BUKOPIN_SYR') {
            $bankName = 'Bank Syariah Bukopin';
        } elseif ($request->get('bank') === 'NUSANTARA_PARAHYANGAN') {
            $bankName = 'Bank Nusantara Parahyangan';
        } elseif ($request->get('bank') === 'JAMBI_UUS') {
            $bankName = 'BPD Jambi UUS';
        } elseif ($request->get('bank') === 'JAWA_TIMUR') {
            $bankName = 'BPD Jawa Timur';
        } elseif ($request->get('bank') === 'MEGA') {
            $bankName = 'Bank Mega';
        } elseif ($request->get('bank') === 'DAERAH_ISTIMEWA_UUS') {
            $bankName = 'BPD Daerah Istimewa Yogyakarta (DIY) UUS';
        } elseif ($request->get('bank') === 'KALIMANTAN_TIMUR') {
            $bankName = 'BPD Kalimantan Timur';
        } elseif ($request->get('bank') === 'MULTI_ARTA_SENTOSA') {
            $bankName = 'Bank Multi Arta Sentosa';
        } elseif ($request->get('bank') === 'OCBC') {
            $bankName = 'Bank OCBC NISP';
        } elseif ($request->get('bank') === 'NATIONALNOBU') {
            $bankName = 'Bank Nationalnobu';
        } elseif ($request->get('bank') === 'BOC') {
            $bankName = 'Bank of China (BOC)';
        } elseif ($request->get('bank') === 'BTN') {
            $bankName = 'Bank Tabungan Negara (BTN)';
        } elseif ($request->get('bank') === 'BENGKULU') {
            $bankName = 'BPD Bengkulu';
        } elseif ($request->get('bank') === 'RESONA') {
            $bankName = 'Bank Resona Perdania';
        } elseif ($request->get('bank') === 'MANDIRI_SYR') {
            $bankName = 'Bank Syariah Mandiri';
        } elseif ($request->get('bank') === 'WOORI') {
            $bankName = 'Bank Woori Indonesia';
        } elseif ($request->get('bank') === 'YUDHA_BHAKTI') {
            $bankName = 'Bank Yudha Bhakti';
        } elseif ($request->get('bank') === 'ACEH') {
            $bankName = 'BPD Aceh';
        } elseif ($request->get('bank') === 'MAYORA') {
            $bankName = 'Bank Mayora';
        } elseif ($request->get('bank') === 'BAML') {
            $bankName = 'Bank of America Merill-Lynch';
        } elseif ($request->get('bank') === 'PERMATA_UUS') {
            $bankName = 'Bank Permata UUS';
        } elseif ($request->get('bank') === 'KESEJAHTERAAN_EKONOMI') {
            $bankName = 'Bank Kesejahteraan Ekonomi';
        } elseif ($request->get('bank') === 'MESTIKA_DHARMA') {
            $bankName = 'Bank Mestika Dharma';
        } elseif ($request->get('bank') === 'OCBC_UUS') {
            $bankName = 'Bank OCBC NISP UUS';
        } elseif ($request->get('bank') === 'RABOBANK') {
            $bankName = 'Bank Rabobank International Indonesia';
        } elseif ($request->get('bank') === 'ROYAL') {
            $bankName = 'Bank Royal Indonesia';
        } elseif ($request->get('bank') === 'MITSUI') {
            $bankName = 'Bank Sumitomo Mitsui Indonesia';
        } elseif ($request->get('bank') === 'UOB') {
            $bankName = 'Bank UOB Indonesia';
        } elseif ($request->get('bank') === 'INDIA') {
            $bankName = 'Bank of India Indonesia';
        } elseif ($request->get('bank') === 'SBI_INDONESIA') {
            $bankName = 'Bank SBI Indonesia';
        } elseif ($request->get('bank') === 'MEGA_SYR') {
            $bankName = 'Bank Syariah Mega';
        } elseif ($request->get('bank') === 'JAMBI') {
            $bankName = 'BPD Jambi';
        } elseif ($request->get('bank') === 'JAWA_TIMUR_UUS') {
            $bankName = 'BPD Jawa Timur UUS';
        } elseif ($request->get('bank') === 'MIZUHO') {
            $bankName = 'Bank Mizuho Indonesia';
        } elseif ($request->get('bank') === 'MNC_INTERNASIONAL') {
            $bankName = 'Bank MNC Internasional';
        } elseif ($request->get('bank') === 'TOKYO') {
            $bankName = 'Bank of Tokyo Mitsubishi UFJ';
        } elseif ($request->get('bank') === 'VICTORIA_SYR') {
            $bankName = 'Bank Victoria Syariah';
        } elseif ($request->get('bank') === 'LAMPUNG') {
            $bankName = 'BPD Lampung';
        } elseif ($request->get('bank') === 'MALUKU') {
            $bankName = 'BPD Maluku';
        } elseif ($request->get('bank') === 'SUMSEL_DAN_BABEL_UUS') {
            $bankName = 'BPD Sumsel Dan Babel UUS';
        } elseif ($request->get('bank') === 'MAYBANK') {
            $bankName = 'Bank Maybank';
        } elseif ($request->get('bank') === 'JPMORGAN') {
            $bankName = 'JP Morgan Chase Bank';
        } elseif ($request->get('bank') === 'SULSELBAR_UUS') {
            $bankName = 'BPD Sulselbar UUS';
        } elseif ($request->get('bank') === 'SULAWESI_TENGGARA') {
            $bankName = 'BPD Sulawesi Tenggara';
        } elseif ($request->get('bank') === 'NUSA_TENGGARA_BARAT') {
            $bankName = 'BPD Nusa Tenggara Barat';
        } elseif ($request->get('bank') === 'RIAU_DAN_KEPRI_UUS') {
            $bankName = 'BPD Riau Dan Kepri UUS';
        } elseif ($request->get('bank') === 'SULUT') {
            $bankName = 'BPD Sulut';
        } elseif ($request->get('bank') === 'SUMUT') {
            $bankName = 'BPD Sumut';
        } elseif ($request->get('bank') === 'DEUTSCHE') {
            $bankName = 'Deutsche Bank';
        } elseif ($request->get('bank') === 'STANDARD_CHARTERED') {
            $bankName = 'Standard Charted Bank';
        } elseif ($request->get('bank') === 'BRI') {
            $bankName = 'Bank Rakyat Indonesia (BRI)';
        } elseif ($request->get('bank') === 'HSBC') {
            $bankName = 'HSBC Indonesia (formerly Bank Ekonomi Raharja)';
        } elseif ($request->get('bank') === 'SULSELBAR') {
            $bankName = 'BPD Sulselbar';
        } elseif ($request->get('bank') === 'SUMATERA_BARAT_UUS') {
            $bankName = 'BPD Sumatera Barat UUS';
        } elseif ($request->get('bank') === 'NUSA_TENGGARA_BARAT_UUS') {
            $bankName = 'BPD Nusa Tenggara Barat UUS';
        } elseif ($request->get('bank') === 'HSBC_UUS') {
            $bankName = 'Hongkong and Shanghai Bank Corporation (HSBC) UUS';
        } elseif ($request->get('bank') === 'PAPUA') {
            $bankName = 'BPD Papua';
        } elseif ($request->get('bank') === 'SULAWESI') {
            $bankName = 'BPD Sulawesi Tengah';
        } elseif ($request->get('bank') === 'SUMATERA_BARAT') {
            $bankName = 'BPD Sumatera Barat';
        } elseif ($request->get('bank') === 'SUMUT_UUS') {
            $bankName = 'BPD Sumut UUS';
        } elseif ($request->get('bank') === 'BNI') {
            $bankName = 'Bank Negara Indonesia (BNI)';
        } elseif ($request->get('bank') === 'PRIMA_MASTER') {
            $bankName = 'Prima Master Bank';
        } elseif ($request->get('bank') === 'MITRA_NIAGA') {
            $bankName = 'Bank Mitra Niaga';
        } elseif ($request->get('bank') === 'NUSA_TENGGARA_TIMUR') {
            $bankName = 'BPD Nusa Tenggara Timur';
        } elseif ($request->get('bank') === 'SUMSEL_DAN_BABEL') {
            $bankName = 'BPD Sumsel Dan Babel';
        } elseif ($request->get('bank') === 'RBS') {
            $bankName = 'Royal Bank of Scotland (RBS)';
        } elseif ($request->get('bank') === 'ARTA_NIAGA_KENCANA') {
            $bankName = 'Bank Arta Niaga Kencana';
        } elseif ($request->get('bank') === 'CITIBANK') {
            $bankName = 'Citibank';
        } elseif ($request->get('bank') === 'RIAU_DAN_KEPRI') {
            $bankName = 'BPD Riau Dan Kepri';
        } elseif ($request->get('bank') === 'CENTRATAMA') {
            $bankName = 'Centratama Nasional Bank';
        } elseif ($request->get('bank') === 'OKE') {
            $bankName = 'Bank Oke Indonesia (formerly Bank Andara)';
        } elseif ($request->get('bank') === 'MANDIRI_ECASH') {
            $bankName = 'Mandiri E-Cash';
        } elseif ($request->get('bank') === 'AMAR') {
            $bankName = 'Bank Amar Indonesia (formerly Anglomas International Bank)';
        } elseif ($request->get('bank') === 'GOPAY') {
            $bankName = 'GoPay';
        } elseif ($request->get('bank') === 'SINARMAS_UUS') {
            $bankName = 'Bank Sinarmas UUS';
        } elseif ($request->get('bank') === 'OVO') {
            $bankName = 'OVO';
        } elseif ($request->get('bank') === 'EXIMBANK') {
            $bankName = 'Indonesia Eximbank (formerly Bank Ekspor Indonesia)';
        } elseif ($request->get('bank') === 'JTRUST') {
            $bankName = 'Bank JTrust Indonesia (formerly Bank Mutiara)';
        } elseif ($request->get('bank') === 'WOORI_SAUDARA') {
            $bankName = 'Bank Woori Saudara Indonesia 1906 (formerly Bank Himpunan Saudara and Bank Woori Indonesia)';
        } elseif ($request->get('bank') === 'BTPN_SYARIAH') {
            $bankName = 'BTPN Syariah (formerly BTPN UUS and Bank Sahabat Purba Danarta)';
        } elseif ($request->get('bank') === 'SHINHAN') {
            $bankName = 'Bank Shinhan Indonesia (formerly Bank Metro Express)';
        } elseif ($request->get('bank') === 'BANTEN') {
            $bankName = 'BPD Banten (formerly Bank Pundi Indonesia)';
        } elseif ($request->get('bank') === 'CCB') {
            $bankName = 'China Construction Bank Indonesia (formerly Bank Antar Daerah and Bank Windu Kentjana International)';
        } elseif ($request->get('bank') === 'MANDIRI_TASPEN') {
            $bankName = 'Mandiri Taspen Pos (formerly Bank Sinar Harapan Bali)';
        } elseif ($request->get('bank') === 'QNB_INDONESIA') {
            $bankName = 'Bank QNB Indonesia (formerly Bank QNB Kesawan)';
        }

        $data = [
            'company_name' => $name,
            'company_email' => $email,
            'bank_name' => $bankName,
            'bank_account_name' => $request->get('bank_account_name'),
            'bank_account_number' => $request->get('bank_account_number'),
            'bank_document' => 'uploads/bank_document/' . $image_name,
        ];

        $template = view('dashboard.company.bank.mail', $data)->render();

        dispatch(new SendEmail($subject, $to, $template));

        updateAchievement($company);

        return json_encode([
            'status' => 200,
            'message' => \trans('bank_provider.store_submit'),
            'success' => \trans('bank_provider.success'),
            'oops' => \trans('general.whoops')
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param mixed $request
     * @param mixed $bank
     *
     * @return void
     */
    public function edit(Request $request, \App\Models\CompanyBank $bank)
    {
        $this->initalize($request);
        if ($bank->id_company != $this->company) {
            return response()->json([
                'status' => 405,
                'message' => 'Action not allowed'
            ]);
        }
        if (auth()->user()->company->is_klhk == 1) {
            return view('klhk.dashboard.company.bank.edit', ['bank' => $bank]);
        }
        return view('dashboard.company.bank.edit', ['bank' => $bank]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param mixed $bank
     *
     * @return void
     */
    public function update(\App\Http\Requests\BankFormRequest $request, \App\Models\CompanyBank $bank)
    {
        //$request = app('App\Http\Requests\BankFormRequest');
        $this->initalize($request);
        if (empty(auth('web')->user()->email)) {
            return apiResponse('403', 'Anda belum mengisi email, silahkan mengisi di pengaturan');
        }
        $this->company = $request->get('my_company');
        if ($bank->id_company != auth()->user()->id_company) {
            return response()->json([
                'status' => 400,
                'message' => 'Action not allowed'
            ]);

        }
        $send = false;
        if ($bank->bank_account_number != $request->get('bank_account_number') || $bank->bank_account_name != $request->get('bank_account_name') || $bank->bank != $request->get('bank') || $request->hasFile('bank_account_document')) {
            $send = true;
        }

        if ($send) {
            if ($request->hasFile('bank_account_document')) {

                $path = public_path('uploads/bank_document');
                $image_name = pathinfo($request->file('bank_account_document')->hashName(), PATHINFO_FILENAME) . '.jpg';
                // Buat folder jika tidak ada
                File::isDirectory($path) or File::makeDirectory($path, 0777, true, true);

                $rotate = (int)$request->input('crop-bank_account_document.rotate');
                $img = Image::make($request->file('bank_account_document'));
                if ($rotate !== 0):
                    $img->rotate(360 - $rotate);
                endif;

                $img->crop((int)$request->input('crop-bank_account_document.width'),
                    (int)$request->input('crop-bank_account_document.height'), (int)$request->input('crop-bank_account_document.x'), (int)$request->input('crop-bank_account_document.y'))
                    ->resize(800, 450, function ($constraint) {
                        return $constraint->aspectRatio();
                    })
                    ->encode('jpeg')
                    ->save($path . '/' . $image_name)
                    ->destroy();

                \DB::transaction(function () use ($bank, $request, $image_name) {

                    $requestbank = $bank->request_changes()->create([
                        'bank' => $request->get('bank'),
                        'bank_account_name' => $request->get('bank_account_name'),
                        'bank_account_number' => $request->get('bank_account_number'),
                        'bank_account_document' => $image_name,
                        'id_user' => auth('web')->id(),
                        'token' => generateRandomString(16),
                        'expired_at' => Carbon::now()->addDays(3)->toDateTimeString()
                    ]);
                    $company = Company::find(auth()->user()->id_company);
                    $user = \App\Models\UserAgent::whereIdCompany($company->id_company)->first();
                    if ($user->email):
                        \Mail::to($user)->sendNow(new BankAccountChangeRequestMail($requestbank, $user));
                    elseif ($user->phone):
                        $message[] = 'Permintaan Penggantian Bank Account';
                        $message[] = 'Data perubahan :';
                        $message[] = 'Bank : '.$request->get('bank');
                        $message[] = 'Nomor Akun Bank : '.$request->get('bank_account_number');
                        $message[] = 'Nama Pemilik Rekening : '.$request->get('bank_account_name');
                        $message[] = 'Tolak : '.(request()->isSecure()?'https://':'http://').$user->company->domain_memoria.'/change-bank-request?u='.$requestbank->id.'&t='.$requestbank->token.'&action=reject';
                        $message[] = 'Setujui : '.(request()->isSecure()?'https://':'http://').$user->company->domain_memoria.'/change-bank-request?u='.$requestbank->id.'&t='.$requestbank->token.'&action=approve';
                        Woowa::SendMessageAsync()->setPhone($user->phone_code.$user->phone)->setMessage(sprintf('%s',implode('\n',$message)))->prepareContent()->send();
                    endif;
                });
            } else {
                \DB::transaction(function () use ($bank, $request) {

                    $requestbank = $bank->request_changes()->create([
                        'bank' => $request->get('bank'),
                        'bank_account_name' => $request->get('bank_account_name'),
                        'bank_account_number' => $request->get('bank_account_number'),
                        'id_user' => auth('web')->id(),
                        'token' => generateRandomString(16),
                        'expired_at' => Carbon::now()->addDays(3)->toDateTimeString()
                    ]);
                    $company = Company::find(auth()->user()->id_company);
                    $user = \App\Models\UserAgent::whereIdCompany($company->id_company)->first();
                    \Mail::to($user)->sendNow(new BankAccountChangeRequestMail($requestbank, $user));
                });
            }
            updateAchievement(auth()->user()->company);
            return response()->json([
                'status' => 200,
                'message' => \trans('bank_provider.request_change_submit'),
                'success' => \trans('bank_provider.success'),
                'oops' => \trans('general.whoops')
            ]);
        }
        updateAchievement(auth()->user()->company);
        return response()->json([
            'status' => 200,
            'message' => \trans('bank_provider.update_submit'),
            'success' => \trans('bank_provider.success'),
            'oops' => \trans('general.whoops')
        ]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param mixed $id
     *
     * @return void
     */
    public function destroy($id)
    {   //For Deleting Users
        \App\Models\CompanyBank::destroy($id);
        return response()->json([
            'status' => 200,
            'message' => 'Bank Account Deleted'
        ]);
    }
}
