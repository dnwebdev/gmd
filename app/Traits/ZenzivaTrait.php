<?php
namespace App\Traits;

trait ZenzivaTrait
{
    public function sendSMS($number, $message)
    {
        $userKey = env('ZENZIVA_USER_KEY');
        $passKey = env('ZENZIVA_PASS_KEY');
        $url =env('ZENZIVA_SMS_URL',"https://reguler.zenziva.net/apps/smsapi.php");
        $curlHandle = curl_init();
        curl_setopt($curlHandle, CURLOPT_URL, $url);
        curl_setopt($curlHandle, CURLOPT_HEADER, 0);
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlHandle, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curlHandle, CURLOPT_TIMEOUT,30);
        curl_setopt($curlHandle, CURLOPT_POST, 1);
        curl_setopt($curlHandle, CURLOPT_POSTFIELDS, array(
            'userkey' => $userKey,
            'passkey' => $passKey,
            'nohp' => $number,
            'pesan' => $message
        ));
        $results = json_decode(curl_exec($curlHandle), true);
        return $results;
    }

    public function sendOTP($number,$otp)
    {
        $userKey = env('ZENZIVA_USER_KEY');
        $passKey = env('ZENZIVA_PASS_KEY');
        $url = env("ZENZIVA_OTP_URL","https://reguler.zenziva.net/apps/smsotp.php");
        $curlHandle = curl_init();
        curl_setopt($curlHandle, CURLOPT_URL, $url);
        curl_setopt($curlHandle, CURLOPT_HEADER, 0);
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlHandle, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curlHandle, CURLOPT_TIMEOUT,30);
        curl_setopt($curlHandle, CURLOPT_POST, 1);
        curl_setopt($curlHandle, CURLOPT_POSTFIELDS, array(
            'userkey' => $userKey,
            'passkey' => $passKey,
            'nohp' => $number,
            'pesan' => $otp
        ));
        $results = json_decode(curl_exec($curlHandle), true);
        return $results;
    }
}