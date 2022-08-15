<?php

namespace App\Http\Controllers\Company\Password;

use App\Http\Requests\Api\Auth\ResetPassword;
use App\Models\UserAgent;
use App\Models\UserOtp;
use App\Traits\ZenzivaTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RequestPasswordController extends Controller
{
    use ZenzivaTrait;

    public function showLinkRequestForm(Request $request)
    {
        toastr();
        if ($request->get('klhk') == 1){
            return view('klhk.auth.new-login.forgot_password_phone');
        }
        return view('auth.new-login.forgot_password_phone');
    }
    public function requestOTP(Request $request)
    {
        $rules = [
            'phone' => 'required'
        ];
        $this->validate($request, $rules);
        \DB::beginTransaction();
        try {
            $email = $request->input('phone');
            $user = UserAgent::where(function ($query) use ($email) {
                $query->where(['email' => $email])->orWhere('phone', $email);
            })->first();
            if (!$user) {
                return apiResponse(404, __('auth.validation.telp.forgot_password', ['attribute' => 'User']));
            }
            if ($user->status == '0') {
                return apiResponse(401, __('auth.not_active'));
            }
            $random = generateRandomString(4, 'number');
            $randomLink = generateRandomString(10);

            if ($request->get('klhk') && $user->company->is_klhk != 1) {
                return apiResponse(404, __('validation.exists'), ['email' => [__('auth.telp')]]);
            }

            while (UserOtp::where('otp', $random)->where('type', 'password_reset')->first()) {
                $random = generateRandomString(4, 'number');
            }
            $otp = UserOtp::create([
                'user_id' => $user->id_user_agen,
                'otp' => $random,
                'shortcode' => $randomLink,
                'type' => 'password_reset'
            ]);
            \DB::commit();

            $message = 'K0DE: '.$random.' Jangan berikan SMS ini ke siapapun';
            $number = $request->input('phone');

            $this->sendOTP($number,$random);
            return apiResponse(200, __('general.success'), [
                'redirect' => url('/password/otp?phone='.$user->phone),
                'submit_url' => url('/password/otp'),
                'id' => $otp->id,
            ]);


        } catch (\Exception $exception) {
            \DB::rollBack();
            return apiResponse(500, __('general.whoops'), getException($exception));
        }
    }

    public function viewActivation(Request $request)
    {
//        $where = [
//            'shortcode'=>$shortcode,
//            'type'=>'password_reset'
//        ];
//        $otp = \App\Models\UserOtp::where($where)->first();
//        if (!$otp){
//            msg('OtP not found',2);
//            return redirect('/');
//        }
//        $user = UserAgent::where('id_user_agen',$otp->user_id)->first();
        $phone = $request->phone;
        if ($request->get('klhk') == 1){
            return view('klhk.auth.new-login.reset_password', compact('phone'));
        }
        return view('auth.new-login.reset_password', compact('phone'));
    }

    public function resetPasswordOTP(ResetPassword $request)
    {
        $userOtp = UserOtp::where('otp', $request->input('otp'))
//            ->where('shortcode', $code)
            ->where('type','password_reset')
            ->first();
        if (!$userOtp){
            return apiResponse(422,trans('validation.invalid_data'),[
                'otp'=>[
                    trans('validation.exists',['attribute'=>'otp'])
                ]
            ]);
        }
        $user = UserAgent::whereIdUserAgen($userOtp->user_id)->first();
        if (!$user){
            return apiResponse(404, __('validation.exists', ['attribute' => 'User']));
        }
        $user->password = bcrypt($request->input('password'));
        $user->save();
        UserOtp::where('user_id',$user->id)->where('type','password_reset')->delete();
        auth()->loginUsingId($user->id_user_agen);
        return apiResponse(200, 'OK');
    }
}
