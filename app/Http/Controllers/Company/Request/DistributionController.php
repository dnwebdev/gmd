<?php

namespace App\Http\Controllers\Company\Request;

use App\Models\DistributionRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DistributionController extends Controller
{
    public function save(Request $request)
    {

        $rules = [
            'email' => 'required|email',
            'phone' => 'required|phone:ID,auto',
            'message' => 'required'
        ];
        $message = [
            'email.required' => trans('custom_validation.distribution.email_required'),
            'phone.required' => trans('custom_validation.distribution.phone_required'),
            'message.required' => trans('custom_validation.distribution.message_required'),
        ];
        $this->validate($request, $rules, $message);

        if ($companyRequest = DistributionRequest::where('company_id', auth()->user()->company->id_company)->where('status','!=', 'reject')->first()) {
            return apiResponse(403, trans('distribution.form.already_exists'));
        }
        DistributionRequest::create([
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'company_id' => auth()->user()->company->id_company,
            'message' => $request->input('message'),
            'status' => 'pending'
        ]);
        $message[] = "``` Permintaan Distribution ";
        $message[] = "========================================";
        $message[] = "Provider : " . auth()->user()->company->company_name;
        $message[] = "Phone    : " . $request->input('phone');
        $message[] = "Email    : " . $request->input('email');
        $message[] = "Message  : " . $request->input('message');
        $message[] = "```";
        if (app()->environment() == 'production'):
            $this->sendDiscord($message);
        endif;
        return apiResponse(200, 'Success');
    }

    private function sendDiscord(array $msg)
    {
        $headers = array(
            'Content-Type:application/json'
        );
        $method = "POST";
        $data['content'] = sprintf('%s', implode("\n", $msg));
        $data = json_encode($data);
        $url = 'https://discordapp.com/api/webhooks/597056669687480320/EwLDJtJk_wZqZbZqu_HNieP7slm6HlbBf8PcZBiurFgU_OhfrKB1B1agP9VopSvmcWe2';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data))
        );
        $result = curl_exec($ch);
        curl_close($ch);
        echo \GuzzleHttp\json_encode($result);
    }
}
