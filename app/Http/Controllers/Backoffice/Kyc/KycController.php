<?php

namespace App\Http\Controllers\Backoffice\Kyc;

use App\Models\Kyc;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class KycController extends Controller
{

    /**
     * KycController constructor.
     */
    public function __construct()
    {
//        $this->middleware('superadmin');
    }

    /**
     * show list page of KYC
     * @param Request $request
     * @param Kyc $kyc
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request, Kyc $kyc)
    {

        if ($request->ajax() && $request->wantsJson()) {
            return $this->loadData($kyc);
        }
        if (\request()->has('status') && \request()->get('status') == 'need_approval') {
            return view('back-office.page.kyc.index-request');
        }

        return view('back-office.page.kyc.index');
    }

    /**
     * provide data for datatables
     * @param $kyc
     * @return mixed
     * @throws \Exception
     */
    public function loadData($kyc)
    {
        $model = $kyc->newQuery()->with('company')->select('tbl_kyc.*');
        if (\request()->has('status') && \request()->get('status') == 'need_approval') {
            $model->where('tbl_kyc.status', 'need_approval');
        }else{
            $model->where('tbl_kyc.status', 'approved');
        }

        return \DataTables::of($model)
            ->addIndexColumn()
            ->addColumn('action', function ($model) {
                $html = '<button data-name="' . $model->business_category_name . '" data-id="' . $model->id . '"  class="btn btn-outline-info btn-preview btn-sm m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill m-btn--air" href=""><i class="fa flaticon-visible"></i></button>';

                return $html;
            })
            ->make(true);
    }

    /**
     * provide detail KYC data
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getData(Request $request)
    {
        $kyc = Kyc::find($request->id);
        if ($kyc) {
            $data = [];
            if ($kyc->company->ownership_status == 'corporate') {
                $data = [
                    [
                        'name' => 'Company Tax Number',
                        'type' => 'image',
                        'url' => asset($kyc->company_tax_number),
                        'origin'=>$kyc->company_tax_number
                    ],
                    [
                        'name' => 'Company Establishment Deed',
                        'type' => 'image',
                        'url' => asset($kyc->company_establishment_deed),
                        'origin'=>$kyc->company_establishment_deed
                    ],
                    [
                        'name' => 'Company Register Certification',
                        'type' => 'image',
                        'url' => asset($kyc->company_register_certification),
                        'origin'=>$kyc->company_register_certification
                    ],
                    [
                        'name' => 'Company Domicile',
                        'type' => 'image',
                        'url' => asset($kyc->company_domicile),
                        'origin'=>$kyc->company_domicile
                    ],
                    [
                        'name' => 'Company Business License',
                        'type' => 'image',
                        'url' => asset($kyc->company_business_license),
                        'origin'=>$kyc->company_business_license
                    ],
                    [
                        'name' => 'Owner Identity Card',
                        'type' => 'image',
                        'url' => asset($kyc->owner_identity_card),
                        'origin'=>$kyc->owner_identity_card
                    ],
                    [
                        'name' => 'Owner Tax Number',
                        'type' => 'image',
                        'url' => asset($kyc->owner_tax_number),
                        'origin'=>$kyc->owner_tax_number
                    ],

                ];
            } else {
                $data = [
                    [
                        'name' => 'Identity Card',
                        'type' => 'image',
                        'url' => asset($kyc->identity_card),
                        'origin'=>$kyc->identity_card
                    ],
                    [
                        'name' => 'Family Card',
                        'type' => 'image',
                        'url' => asset($kyc->family_card),
                        'origin'=>$kyc->family_card
                    ],
                    [
                        'name' => 'Tax Number',
                        'type' => 'image',
                        'url' => asset($kyc->tax_number),
                        'origin'=>$kyc->tax_number
                    ],
                    [
                        'name' => 'Police Certificate',
                        'type' => 'image',
                        'url' => asset($kyc->police_certificate),
                        'origin'=>$kyc->police_certificate
                    ],
                    [
                        'name' => 'Bank Statement',
                        'type' => 'image',
                        'url' => asset($kyc->bank_statement),
                        'origin'=>$kyc->bank_statement
                    ],
                    [
                        'name' => 'Photo',
                        'type' => 'image',
                        'url' => asset($kyc->photo),
                        'origin'=>$kyc->photo
                    ],
                    [
                        'name' => 'Phone Number',
                        'type'=>'text',
                        'url' => $kyc->phone_number
                    ],
                    [
                        'name' => 'Address',
                        'type'=>'text',
                        'url' => $kyc->address
                    ],

                ];
            }
            return apiResponse(200,'OK',$data);
        }

        return apiResponse(404,'Not Found');
    }

    /**
     * Download File
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download(Request $request)
    {
        return response()->download(public_path($request->get('url')));
    }

    /**
     * action to approve KYC
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function approve(Request $request)
    {
        if ($kyc = Kyc::whereId($request->get('id'))->where('status','need_approval')->first()){
            $kyc->update(['status'=>'approved']);
            return apiResponse(200,'Approved');
        }
        return apiResponse(404,'Not Found');
    }

    /**
     * Action to reject KYC
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reject(Request $request)
    {
        if ($kyc = Kyc::whereId($request->get('id'))->where('status','need_approval')->first()){
            $kyc->update(['status'=>'rejected']);
            return apiResponse(200,'Rejected');
        }
        return apiResponse(404,'Not Found');
    }
}
