<?php

namespace App\Http\Controllers\Company\Kyc;

use App\Models\Company;
use App\Traits\DiscordTrait;
use Carbon\Carbon;
use File;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Image;
use Storage;

class KycCtrl extends Controller
{

    use DiscordTrait;
    /**
     * function load data index kyc
     *
     * @return void
     */
    public function index()
    {
        if (auth()->user()->company->is_klhk == 1){
            return view('klhk.dashboard.company.kyc.index');
        }
        return view('dashboard.company.kyc.index');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  mixed $request
     *
     * @return void
     */
    public function update(Request $request)
    {

        $user = \Auth::guard('web')->user();
        $company = Company::find($user->company->id_company);

        if ($company->kyc){
            if ($company->ownership_status ==='corporate'){
                $rule = [
                    'company_tax_number'=>'nullable|image',
                    'company_establishment_deed'=>'nullable|image',
                    'company_register_certification'=>'nullable|image',
                    'company_domicile'=>'nullable|image',
                    'company_business_license'=>'nullable|image',
                    'owner_identity_card'=>'nullable|image',
                    'owner_tax_number'=>'nullable|image',
                ];
                $this->validate($request,$rule);
                $updated = false;
                try{
                    \DB::beginTransaction();
                    $path = 'uploads/kyc/'.$company->id_company;
                    if (!File::isDirectory(Storage::disk('public')->path($path))) {
                        File::makeDirectory(Storage::disk('public')->path($path), 0777, true, true);
                    }
                    $data = [];
                    $deletes=[];
                    $dataUpdated = [];
                    foreach ($request->deleted_files as $deleted_file){
                        if ($deleted_file!==null || $deleted_file!=''){
                            $updated = true;
                            $data[$deleted_file] = null;
                            $deletes[] = $company->kyc->$deleted_file;
                        }

                    }
                    foreach ($request->files as $keyname=>$file){
                        if ($file) {
                            $source = $file;
                            $name = 'kyc-' . generateRandomString(6) . time() . '.' . $source->getClientOriginalExtension();
                            if (Image::make($source)->save(Storage::disk('public')->path($path . '/' . $name))) {
                                $data[$keyname] = Storage::url($path . '/' . $name);
                                $deletes[] = $company->kyc->$keyname;
                                $dataUpdated[] = ucfirst(str_replace('_',' ',$keyname));
                                $updated = true;
                            }
                        }
                    }
                    if ($updated){
                        $data['status'] = 'need_approval';
                        $company->kyc->update($data);
                    }
                    \DB::commit();
                    foreach ($deletes as $delete){
                        if (\File::exists(public_path($delete))) {
                            File::delete(public_path($delete));
                        }
                    }
                    if ($updated){
                        $ip = isset($_SERVER['HTTP_CF_CONNECTING_IP']) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : $request->ip();
                        $newCompany = $company;
                        $loc = \Stevebauman\Location\Facades\Location::get($ip);
                        $content = '**Document KYC has been changed ' . Carbon::now()->format('d M Y H:i:s') . '**';
                        $content .= '```';
                        $content .= 'Company Name : ' . $newCompany->company_name . '
Domain Gomodo : https://' . $newCompany->domain_memoria . '
Company Ownership: ' . $newCompany->ownership_status . '
IP Address : '.$ip.'
City name : '.$loc->cityName.'
Region Name : '.$loc->regionName.'
Country Code : '.$loc->countryCode.' 
File Yang Diunggah :
 
';
                        foreach ($dataUpdated as $item){
$content.=$item.'
';
                        }
                        $content .= '```';

                        $this->sendDiscordNotification($content,'kyc');



                        return apiResponse(200,trans('kyc.response.ok'));
                    }
                    return apiResponse(200,trans('kyc.response.nothing_change'));
                }catch (\Exception $exception){
                    \DB::rollBack();
                    return apiResponse(500,'',getException($exception));
                }


            }else{
                $rule = [
                    'identity_card'=>'nullable|image',
                    'family_card'=>'nullable|image',
                    'tax_number'=>'nullable|image',
                    'police_certificate'=>'nullable|image',
                    'bank_statement'=>'nullable|image',
                    'photo'=>'nullable|image',
                    'phone_number'=>'required|numeric|digits_between:6,20',
                    'address'=>'required',
                ];
                $this->validate($request,$rule);
                $updated = false;
                try {
                    \DB::beginTransaction();
                    $path = 'uploads/kyc/' . $company->id_company;
                    if (!File::isDirectory(Storage::disk('public')->path($path))) {
                        File::makeDirectory(Storage::disk('public')->path($path), 0777, true, true);
                    }

                    $data = [];
                    $deletes=[];
                    $dataUpdated=[];
                    foreach ($request->deleted_files as $deleted_file){
                        if ($deleted_file!==null || $deleted_file!=''){
                            $updated = true;
                            $data[$deleted_file] = null;
                            $deletes[] = $company->kyc->$deleted_file;
                        }

                    }
                    foreach ($request->files as $keyname=>$file){
                        if ($file) {
                            $source = $file;
                            $name = 'kyc-' . generateRandomString(6) . time() . '.' . $source->getClientOriginalExtension();
                            if (Image::make($source)->save(Storage::disk('public')->path($path . '/' . $name))) {
                                $data[$keyname] = Storage::url($path . '/' . $name);
                                $deletes[] = $company->kyc->$keyname;
                                $dataUpdated[] = ucfirst(str_replace('_',' ',$keyname));
                                $updated = true;
                            }
                        }
                    }
                    if ($company->kyc->phone_number!=$request->input('phone_number') || $company->kyc->address != $request->input('address')){
                        $updated = true;
                        $data['phone_number'] = $request->input('phone_number');
                        $data['address'] = $request->input('address');
                    }
                    if ($updated){
                        $data['status'] = 'need_approval';
                        $company->kyc->update($data);
                    }
                    \DB::commit();
                    foreach ($deletes as $delete){
                        if (\File::exists(public_path($delete))) {
                            File::delete(public_path($delete));
                        }
                    }
                    if ($updated){
                        $ip = isset($_SERVER['HTTP_CF_CONNECTING_IP']) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : $request->ip();
                        $newCompany = $company;
                        $loc = \Stevebauman\Location\Facades\Location::get($ip);
                        $content = '**Document KYC has been changed ' . Carbon::now()->format('d M Y H:i:s') . '**';
                        $content .= '```';
                        $content .= 'Company Name : ' . $newCompany->company_name . '
Domain Gomodo : https://' . $newCompany->domain_memoria . '
Company Ownership: ' . $newCompany->ownership_status . '
IP Address : '.$ip.'
City name : '.$loc->cityName.'
Region Name : '.$loc->regionName.'
Country Code : '.$loc->countryCode.' 
File Yang Diunggah : 

';
                        foreach ($dataUpdated as $item){
$content.=$item.'
';
                        }
                        $content .= '```';

                        $this->sendDiscordNotification($content,'kyc');
                        return apiResponse(200,trans('kyc.response.ok'));
                    }
                    return apiResponse(200,trans('kyc.response.nothing_change'));
                }catch (\Exception $exception){
                    \DB::rollBack();
                    return apiResponse(500,'',getException($exception));
                }
            }

        }else{
            if ($company->ownership_status ==='corporate'){
                $rule = [
                  'company_tax_number'=>'required|image',
                  'company_establishment_deed'=>'required|image',
                  'company_register_certification'=>'required|image',
                  'company_domicile'=>'required|image',
                  'company_business_license'=>'required|image',
                  'owner_identity_card'=>'required|image',
                  'owner_tax_number'=>'required|image',
                ];
                $this->validate($request,$rule);
                try{
                    \DB::beginTransaction();
                    $path = 'uploads/kyc/'.$company->id_company;
                    if (!File::isDirectory(Storage::disk('public')->path($path))) {
                        File::makeDirectory(Storage::disk('public')->path($path), 0777, true, true);
                    }
                    $data = [];
                    foreach ($request->files as $keyname=>$file){
                        $source = $file;
                        $name = 'kyc-'.generateRandomString(6) . time() . '.' . $source->getClientOriginalExtension();
                        if (Image::make($source)->save(Storage::disk('public')->path($path . '/' . $name))) {
                            $data[$keyname] = Storage::url($path.'/'.$name);
                        }
                    }
                    $company->kyc()->create($data);
                    \DB::commit();
                    $ip = isset($_SERVER['HTTP_CF_CONNECTING_IP']) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : $request->ip();
                    $newCompany = $company;
                    $loc = \Stevebauman\Location\Facades\Location::get($ip);
                    $content = '**New KYC Request ' . Carbon::now()->format('d M Y H:i:s') . '**';
                    $content .= '```';
                    $content .= 'Company Name : ' . $newCompany->company_name . '
Domain Gomodo : https://' . $newCompany->domain_memoria . '
Company Ownership: ' . $newCompany->ownership_status . '
IP Address : '.$ip.'
City name : '.$loc->cityName.'
Region Name : '.$loc->regionName.'
Country Code : '.$loc->countryCode;
                    $content .= '```';


                    $this->sendDiscordNotification($content,'kyc');
                    return apiResponse(200,'OK');
                }catch (\Exception $exception){
                    \DB::rollBack();
                    return apiResponse(500,'',getException($exception));
                }
            }else{
                $rule = [
                    'identity_card'=>'required|image',
                    'family_card'=>'required|image',
                    'tax_number'=>'required|image',
                    'police_certificate'=>'sometimes|image',
                    'bank_statement'=>'sometimes|image',
                    'photo'=>'sometimes|image',
                    'phone_number'=>'required|numeric|digits_between:6,20',
                    'address'=>'required',
                ];
                $this->validate($request,$rule);
                try{
                    \DB::beginTransaction();
                    $path = 'uploads/kyc/'.$company->id_company;
                    if (!File::isDirectory(Storage::disk('public')->path($path))) {
                        File::makeDirectory(Storage::disk('public')->path($path), 0777, true, true);
                    }
                    $data = [];
                    foreach ($request->files as $keyname=>$file){
                        $source = $file;
                        $name = 'kyc-'.generateRandomString(6) . time() . '.' . $source->getClientOriginalExtension();
                        if (Image::make($source)->save(Storage::disk('public')->path($path . '/' . $name))) {
                            $data[$keyname] = Storage::url($path.'/'.$name);
                        }
                    }
                    $data['phone_number'] = $request->input('phone_number');
                    $data['address'] = $request->input('address');
                    $company->kyc()->create($data);
                    \DB::commit();
                    $ip = isset($_SERVER['HTTP_CF_CONNECTING_IP']) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : $request->ip();
                    $newCompany = $company;
                    $loc = \Stevebauman\Location\Facades\Location::get($ip);
                    $content = '**New KYC Request ' . Carbon::now()->format('d M Y H:i:s') . '**';
                    $content .= '```';
                    $content .= 'Company Name : ' . $newCompany->company_name . '
Domain Gomodo : https://' . $newCompany->domain_memoria . '
Company Ownership: ' . $newCompany->ownership_status . '
IP Address : '.$ip.'
City name : '.$loc->cityName.'
Region Name : '.$loc->regionName.'
Country Code : '.$loc->countryCode;
                    $content .= '```';

                    $this->sendDiscordNotification($content,'kyc');
                    updateAchievement($newCompany);
                    return apiResponse(200,'OK');
                }catch (\Exception $exception){
                    \DB::rollBack();
                    return apiResponse(500,'',getException($exception));
                }
            }




        }

    }
}
