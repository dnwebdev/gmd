<?php

namespace App\Http\Controllers\Backoffice\Company;

use App\Exports\ProviderExport;
use App\Models\Association;
use App\Models\Company;
use App\Models\GoogleReviewWidget;
use App\Models\UserAgent;
use App\Scopes\ActiveProviderScope;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

class CompanyCtrl extends Controller
{
    /**
     * CompanyCtrl constructor.
     */
    public function __construct()
    {
        $this->middleware('superadmin')->except(['loadAjaxData', 'index', 'edit']);
    }

    /**
     * show view list of providers
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return viewKlhk('back-office.page.company.index', 'new-backoffice.member.list_member');
    }


    /**
     * load data for datatable
     *
     * @return mixed
     * @throws \Exception
     */
    public function loadAjaxData()
    {
        $models = Company::withoutGlobalScope(ActiveProviderScope::class);

        return \DataTables::of($models)
            ->addIndexColumn()
            ->addColumn('action', function ($model) {
                if (request()->is_klhk) {
                    $html = '<a href="'.route('admin:providers.edit',
                            ['id' => $model->id_company]).'" data-name="'.$model->company_name.'" data-id="'.$model->id.'"  data-popup="tooltip" title="Edit"><i class="icon-pencil"></i></a>';
                } else {
                    $html = '<a href="'.route('admin:providers.edit',
                            ['id' => $model->id_company]).'" data-name="'.$model->company_name.'" data-id="'.$model->id.'"  class="btn btn-outline-info btn-preview btn-sm m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill m-btn--air" href=""><i class="fa flaticon-visible"></i></a>';
                }

                return $html;
            })
            ->editColumn('status', function ($model) {
                if ($model->status == '0') {
                    return '<span class="badge badge-danger">Tidak Aktif</span>';
                }
                return '<span class="badge badge-success">Aktif</span>';
            })
            ->editColumn('domain_memoria', function ($model) {
                if ($model->domain_memoria == null) {
                    return '-';
                }
                return '<a href="http://'.$model->domain_memoria.'" target="_blank">'.$model->domain_memoria.'</a>';
            })
            ->rawColumns(['action', 'domain_memoria', 'status'])
            ->make(true);
    }

    /**
     * show edit form provider
     *
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function edit($id)
    {
        if ($company = Company::withoutGlobalScope(ActiveProviderScope::class)->where('id_company', $id)->first()) {
            $users = $company->id_user_agen;
            $associations = $company->associations;
            \JavaScript::put([
                'edit_company' => route('admin:providers.update-company', ['id' => $id]),
                'edit_user' => route('admin:providers.update-user', ['id' => $id])
            ]);
            return viewKlhk(['back-office.page.company.edit', compact('company', 'users', 'associations')],
                ['new-backoffice.member.edit_member', compact('company', 'users', 'associations')]);
        }
        msg('Company not Found', 2);
        return redirect()->route('admin:providers.index');
    }

    /**
     * superadmin login as provider
     *
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function loginAsUser($id)
    {
        if ($company = Company::find($id)) {
            auth('web')->logout();
            auth('web')->loginUsingId($company->agent->id_user_agen);

            return redirect(route('company.dashboard'));
        }
        msg('Company not Found', 2);
        return redirect()->route('admin:providers.index');
    }

    /**
     * update Provider
     *
     * @param           $id
     * @param  Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveCompany($id, Request $request)
    {
        if ($company = Company::withoutGlobalScope(ActiveProviderScope::class)->where('id_company', $id)->first()) {
            $rules = [
                'company_name' => 'required|max:100',
                'domain_memoria' => [
                    'required',
                    'max:100',
                    Rule::unique('tbl_company', 'domain_memoria')->whereNot('id_company', $id)
                ],
                'email_company' => 'nullable|email|max:100',
                'phone_company' => 'nullable|numeric|digits_between:6,20',
                'ownership_status' => 'required|in:personal,corporate',
                'verified_provider' => 'required|in:0,1',
                'status' => 'required|in:0,1',
                'is_klhk' => 'required|in:0,1',
                'ga_code' => 'nullable',
                'about' => 'nullable|max:1000',
            ];
            $this->validate($request, $rules);
            $updateKYC = false;
            try {
                \DB::beginTransaction();
                if ($request->input('ownership_status') != $company->ownership_status) {
                    $updateKYC = true;
                }
                $company->update([
                    'company_name' => $request->input('company_name'),
                    'domain_memoria' => $request->input('domain_memoria'),
                    'email_company' => $request->input('email_company'),
                    'phone_company' => $request->input('phone_company'),
                    'ownership_status' => $request->input('ownership_status'),
                    'verified_provider' => $request->input('verified_provider'),
                    'status' => $request->input('status'),
                    'ga_code' => $request->input('ga_code'),
                    'about' => $request->input('about'),
                    'is_klhk' => $request->input('is_klhk'),
                ]);
                if ($updateKYC) {
                    if ($company->kyc) {
                        $company->kyc->delete();
                    }
                }
                \DB::commit();
                return apiResponse(200, __('backoffice.provider.updated'), ['redirect' => route('admin:providers.index')]);
            } catch (\Exception $exception) {
                \DB::rollBack();
                return apiResponse(500, __('general.whoops'), getException($exception));
            }
        }

        return apiResponse(404, 'Not Found');
    }

    /**
     * update login provider
     *
     * @param           $id
     * @param  Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveUser($id, Request $request)
    {
        if ($company = UserAgent::whereIdUserAgen($request->input('id_user_agen'))->where('id_company', $id)->first()) {
            $rules = [
                'first_name' => 'required|max:100',
                'last_name' => 'nullable|max:100',
                'email' => 'nullable|email|max:100',
                'password' => 'nullable|max:100:min:6',
                'phone' => 'nullable|numeric|digits_between:6,20',


            ];
            $this->validate($request, $rules);
            try {
                \DB::beginTransaction();
                $company->update([
                    'first_name' => $request->input('first_name'),
                    'last_name' => $request->input('last_name'),
                    'email' => $request->input('email'),
                    'phone' => $request->input('phone'),
                    'status' => $request->has('status') ? $request->input('status') : 0
                ]);
                if (checkRequestExists($request, 'password', 'POST')) {
                    $company->update([
                        'password' => bcrypt($request->input('password'))
                    ]);
                }

                \DB::commit();
                return apiResponse(200, 'Updated', ['redirect' => route('admin:providers.index')]);
            } catch (\Exception $exception) {
                \DB::rollBack();
                return apiResponse(500, __('general.whoops'), getException($exception));
            }
        }

        return apiResponse(404, 'Not Found');
    }

    /**
     * update bank account provider
     *
     * @param           $id
     * @param  Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveBank($id, Request $request)
    {
        if ($company = Company::find($id)) {
            $send = false;


            $rules = [
                'bank' => 'required',
                'bank_account_name' => 'required|max:100',
                'bank_account_number' => 'numeric|digits_between:6,50'
            ];
            $this->validate($request, $rules);

            try {
                \DB::beginTransaction();
                if ($company->bank) {
                    if ($company->bank->bank_account_number != $request->get('bank_account_number') || $company->bank->bank_account_name != $request->get('bank_account_name')) {
                        $send = true;
                    }
                    $company->bank->update([
                        'bank' => $request->input('bank'),
                        'bank_account_name' => $request->input('bank_account_name'),
                        'bank_account_number' => $request->input('bank_account_number'),
                    ]);

                } else {
                    $company->bank->create([
                        'bank' => $request->input('bank'),
                        'bank_account_name' => $request->input('bank_account_name'),
                        'bank_account_number' => $request->input('bank_account_number'),
                    ]);
                }
                \DB::commit();
                if ($send) {
                    $user = \App\Models\UserAgent::whereIdCompany($company->id_company)->first();
                    $bank = $company->bank;
                    $data = compact('bank', 'user');

                    if (!empty($user->email)) {
                        \Mail::send('mail.bank.change-account', $data, function ($message) use ($data) {
                            $message->to($data['user']->email);
                            $message->subject(trans('email_bank_account.title'));
                            $message->from('info@gomodo.tech', 'Gomodo Technologies');

                        });
                    }
                }
                return apiResponse(200, 'Updated', ['redirect' => route('admin:providers.index')]);
            } catch (\Exception $exception) {
                \DB::rollBack();
                return apiResponse(500, __('general.whoops'), getException($exception));
            }
        }
        return apiResponse(404, 'Not Found');
    }

    /**
     * update association for provider
     *
     * @param           $id
     * @param  Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveAssociation($id, Request $request)
    {
        if ($company = Company::find($id)) {

            $rules = [
                'association_id' => 'required|exists:tbl_association,id',
                'membership_id' => 'nullable|max:100',

            ];
            $this->validate($request, $rules);

            try {
                \DB::beginTransaction();
                $association = Association::find($request->association_id);

                if ($compass = $company->associations()->where('id', $request->association_id)->first()) {
                    \DB::rollBack();
                    return apiResponse(400, 'Already a member of that association ', []);
                } else {

                    $max = env('MAX_ASSOCIATION', 3);
                    if ($company->associations->count() >= $max) {
                        \DB::rollBack();
                        return apiResponse(500, 'Max Association ('.$max.') reached ', []);
                    }
                    if (!checkRequestExists($request, 'membership_id', 'POST')) {
                        $membershipID = $association->id.'-'.generateRandomString(6);
                        while (\DB::table('tbl_company_association')->where('membership_id', $membershipID)->first()) {
                            $membershipID = $association->id.'-'.generateRandomString(6);
                        }
                    } else {
                        $membershipID = $request->input('membership_id');
                    }
                    $company->associations()->attach($request->association_id, ['membership_id' => $membershipID]);
                }
                \DB::commit();
                return apiResponse(200, 'Updated', ['redirect' => route('admin:providers.edit', ['id' => $id])]);
            } catch (\Exception $exception) {
                \DB::rollBack();
                return apiResponse(500, __('general.whoops'), getException($exception));
            }
        }
        return apiResponse(404, 'Not Found');

    }

    /**
     * delete provider association
     *
     * @param           $id
     * @param  Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteAssociation($id, Request $request)
    {
        if ($company = Company::find($id)) {
            $rules = [
                'association_id' => 'required|exists:tbl_association,id',
            ];
            $this->validate($request, $rules);

            try {
                \DB::beginTransaction();
                if ($company->associations()->where('id', $request->association_id)->first()) {
                    $company->associations()->detach($request->association_id);
                }
                \DB::commit();
                return apiResponse(200, 'Updated', ['redirect' => route('admin:providers.edit', ['id' => $id])]);
            } catch (\Exception $exception) {
                \DB::rollBack();
                return apiResponse(500, __('general.whoops'), getException($exception));
            }
        }
        return apiResponse(404, 'Not Found');
    }

    public function saveGoogleWidget($id, Request $request)
    {
        if ($company = Company::find($id)) {
            $rule = [
                'widget_script' => 'required'
            ];
            $this->validate($request, $rule);
            try {
                \DB::beginTransaction();
                $where = ['company_id'=>$company->id_company];
                GoogleReviewWidget::updateOrCreate($where,['widget_script'=>$request->input('widget_script')]);
                DB::commit();
                return apiResponse(200, 'Updated', ['redirect' => route('admin:providers.edit', ['id' => $id])]);
            } catch (\Exception $exception) {
                \DB::rollBack();
                return apiResponse(500, __('general.whoops'), getException($exception));
            }
        }
        return apiResponse(404, 'Not Found');
    }

    public function export(Request $request)
    {
        // Function params
        $params = ['all'];

        $file_name[1] = 'Provider';
        if ($request->has('hasSuccessfulTransaction')) {
            $ex = explode(' - ', $request->input('range'));
            $file_name[2] = ' - transaksi sukses ';
            $file_name[3] = ' - '.implode(' - ', array_map(function ($date) {
                    return Carbon::parse($date)->format('d M Y');
                }, $ex));

            $params = ['hasSuccessfulTransaction', $ex[0], $ex[1]];
        }

        if (request()->is_klhk) {
            $file_name[1] = 'Penyedia Ekowisata';
        }

        return (new ProviderExport(...$params))->download(implode(' ', $file_name).'.xls');
    }
}
