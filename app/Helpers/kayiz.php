<?php

/**
 * Created by PhpStorm.
 * User: kayiz
 * Date: 09/08/2017
 * Time: 09.54
 */

if (!function_exists('getException')) {
    function getException($exception)
    {
        $error = [
            'exception_code' => $exception->getCode(),
            'exception_file' => $exception->getFile(),
            'exception_line' => $exception->getLine(),
            'exception_message' => clean_message($exception->getMessage()),
            'exception_trace' => $exception->getTrace(),
            'exception_previous' => $exception->getPrevious(),
            'exception_traceString' => $exception->getTraceAsString(),
        ];

        return $error;
    }
}

if (!function_exists('SmsSend')) {
    function SmsSend($number, $message)
    {
        $userKey = env('ZENZIVA_USER_KEY');
        $passKey = env('ZENZIVA_PASS_KEY');
        $url = "https://reguler.zenziva.net/apps/smsapi.php";
        $curlHandle = curl_init();
        curl_setopt($curlHandle, CURLOPT_URL, $url);
        curl_setopt($curlHandle, CURLOPT_POSTFIELDS,
            'userkey=' . $userKey . '&passkey=' . $passKey . '&nohp=' . $number . '&pesan=' . urlencode($message));
        curl_setopt($curlHandle, CURLOPT_HEADER, 0);
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlHandle, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curlHandle, CURLOPT_TIMEOUT, 30);
        curl_setopt($curlHandle, CURLOPT_POST, 1);
        $results = curl_exec($curlHandle);
        curl_close($curlHandle);
        $XMLdata = new \SimpleXMLElement($results);
        $status = $XMLdata;
        return $status;
    }
}
if (!function_exists('toObject')) {
    function toObject(array $array)
    {
        return json_decode(json_encode($array), false);
    }
}

if (!function_exists('renderStatusOrder')) {
    function renderStatusOrder($order, bool $klhk = false)
    {
        $html = '<span class="badge-pill pl-3 pr-3 pt-2 pb-2 ';
        switch ($order->status) {
            case 0:
                $class = $klhk ? 'badge badge-warning give-position-right badge-pill p-2' : 'badge-pill pl-3 pr-3 pt-2 pb-2 badge-warning';
                break;
            case 1:
                $class = $klhk ? 'badge badge-success give-position-right badge-pill p-2' : 'badge-pill pl-3 pr-3 pt-2 pb-2 badge-success';
                break;
            case 7:
                $class = $klhk ? 'badge badge-danger give-position-right badge-pill p-2' : 'badge-pill pl-3 pr-3 pt-2 pb-2 badge-danger';
                break;
            default:
                $class = $klhk ? 'badge badge-secondary give-position-right badge-pill p-2' : 'badge-pill pl-3 pr-3 pt-2 pb-2 badge-primary';
                break;
        }

        return '<span class="' . $class . '">' . $order->status_text . '</span>';
    }
}


if (!function_exists('sendMessage')) {
    function sendMessage(
        $receivers = [],
        $title = "NLEC Nihongo",
        $body = "Welcome",
        $data = ['foo' => 'bar'],
        $url = 'https://nlecnihongo.com'
    )
    {


        $headings = array(
            "en" => $title
        );
        $content = array(
            "en" => $body
        );

        $fields = array(
            'app_id' => '1c36bb9e-c7ca-4808-bcef-a1cb0291b809',
            'include_player_ids' => $receivers,
            'data' => $data,
            'contents' => $content,
            'headings' => $headings,
            'url' => $url
        );

        $fields = json_encode($fields);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }
}
if (!function_exists('toastr')) {
    function toastr()
    {
        $js = [];
        if (\Session::has('failed')) {
            $js['error'] = \Session::get('failed');
        }
        if (\Session::has('info')) {
            $js['info'] = \Session::get('info');
        }
        if (\Session::has('warning')) {
            $js['warning'] = \Session::get('warning');
        }
        if (\Session::has('success')) {
            $js['success'] = \Session::get('success');
        }
        \JavaScript::put($js);
    }
}
if (!function_exists('msg')) {
    function msg($msg = 'ok', $status = 1)
    {
        switch ($status) {
            case 1:
                $type = 'success';
                break;
            case 2:
                $type = 'failed';
                break;
            case 3:
                $type = 'warning';
                break;
            default;
                $type = 'success';

        }

        Session::flash($type, clean_message($msg));
    }
}
if (!function_exists('checkRequestExists')) {
    function checkRequestExists($request, $key, $type = 'get')
    {

        if ($type === 'get') {
            if ($request->has($key) && $request->get($key) !== null) {
                return true;
            }
            return false;
        }
        if ($request->has($key) && $request->input($key) !== null) {
            return true;
        }
        return false;
    }
}


if (!function_exists('getUser')) {
    function getUser($guard = 'api')
    {
        return auth($guard)->user();
    }
}

if (!function_exists('getUserID')) {
    function getUserID($guard = 'api')
    {
        return auth($guard)->user()->id;
    }
}


if (!function_exists('sendFireBaseNotif')) {
    function sendFireBaseNotif($to, $data)
    {
        $firebaseKey = env('GCM_KEY');
        $url = env('GCM_URL', 'https://fcm.googleapis.com/fcm/send');
        $client = new GuzzleHttp\Client();
        $json = [
            "to" => $to,
            "data" => $data
        ];
        try {
            $res = json_decode($client->post($url, [
                'headers' => [
                    "Content-Type" => "application/json",
                    "Authorization" => "key=" . $firebaseKey
                ],
                "connect_timeout" => 100,
                "json" => $json
            ])->getBody());
            return $res;
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            return $e->getMessage();
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }

    }
}

if (!function_exists('skema')) {
    function skema($data)
    {
        echo "<pre>";
        print_r($data);
        echo "</pre>";
    }
}

if (!function_exists('clean_message')) {
    function clean_message($message)
    {
        return str_replace("'", "\'", $message);
    }
}

if (!function_exists('setPagination')) {
    function setPagination($data, $paginate)
    {
        $result = [
            "current_page" => $paginate->currentPage(),
            'data' => $data,
            "first_page_url" => $paginate->url(1),
            "from" => $paginate->firstItem(),
            "last_page" => $paginate->lastPage(),
            "last_page_url" => $paginate->url($paginate->lastPage()),
            "next_page_url" => $paginate->nextPageUrl(),
            "path" => url()->current(),
            "per_page" => $paginate->perPage(),
            "prev_page_url" => $paginate->previousPageUrl(),
            "to" => $paginate->lastItem(),
            "total" => $paginate->total(),
        ];
        return $result;
    }
}

if (!function_exists('setSimplePagination')) {
    function setSimplePagination($data, $paginate)
    {
        $result = [
            "current_page" => $paginate->currentPage(),
            'data' => $data,
            "from" => $paginate->firstItem(),
            "next_page_url" => $paginate->nextPageUrl(),
//            "path" => url()->current(),
            "per_page" => $paginate->perPage(),
            "prev_page_url" => $paginate->previousPageUrl(),
            "to" => $paginate->lastItem(),
        ];
        return $result;
    }
}

if (!function_exists('apiResponse')) {
    function apiResponse($code = 200, $message = '', $data = null, $validation = null)
    {
//        if ($code == 500 && env('APP_ENV') == 'production') {
//            $result = [
//                'message' => 'Internal Server Error',
//                'result' => $data,
//                'errors' => $validation
//            ];
//            return response()->json($result, 500);
//        }
        $result = [
            'message' => $message,
            'result' => $data,
            'errors' => $validation
        ];
//        dd($result);
        return response()->json($result, $code);
    }
}

if (!function_exists('build_slug')) {
    function build_slug($plain = '', $number = 0)
    {
        if ((int)$number <= 0) {
            return str_slug($plain);
        } else {
            $s = '';
            $e = '';
            for ($i = 0; $i < $number; $i++) {
                $s .= '1';
                $e .= '9';
            }
            return str_slug($plain) . '-' . rand((int)$s, (int)$e);
        }
    }
}
if (!function_exists('generateRandomString')) {
    function generateRandomString($length = 10, $type = 'all')
    {
        switch ($type) {
            case 'number':
                $characters = '0123456789';
                break;
            case 'word':
                $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                break;
            default:
                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }

        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}


if (!function_exists('format_priceID')) {
    function format_priceID($data, $prefix = 'Rp', $separator = '.')
    {
        return $prefix . ' ' . number_format((double)$data, 0, '', $separator);
    }
}

if (!function_exists('imgLink')) {
    function imgLink($link, $default = null)
    {
        if ($link != null) {
            $split = explode('//', $link);
            $allowed = ['http:', 'htpps:'];
            if (in_array($split[0], $allowed)) {
                return $link;
            }
            return asset($link);
        }
        if ($default == null) {
            return asset(env('DEFAULT_IMAGE', 'http://placehold.it/400x400'));
        }
        return asset($default);
    }
}

if (!function_exists('setOuterLink')) {
    function setOuterLink($link, $type = '')
    {
        if ($link != null) {
            $split = explode('//', $link);
            $allowed = ['http:', 'https:'];
            if (in_array($split[0], $allowed)) {
                return $link;
            }
            switch ($type) {
                case 'http':
                    return 'http://' . $link;
                    break;
                case 'https':
                    return 'https://' . $link;
                    break;

                default:
                    return 'http://' . $link;
            }
        }

    }
}


if (!function_exists('getEnum')) {
    function getEnum($table, $column)
    {
        $type = DB::select(DB::raw("SHOW COLUMNS FROM $table WHERE Field = '$column'"))[0]->Type;
        preg_match('/^enum\((.*)\)$/', $type, $matches);
        $enum = array();
        foreach (explode(',', $matches[1]) as $value) {
            $v = trim($value, "'");
            $enum = array_add($enum, $v, $v);
        }
        return $enum;
    }
}

if (!function_exists('hex2bin')) {
    function hex2bin($str)
    {
        $sbin = "";
        $len = strlen($str);
        for ($i = 0; $i < $len; $i += 2) {
            $sbin .= pack("H*", substr($str, $i, 2));
        }

        return $sbin;
    }
}
if (!function_exists('pdu2str')) {
    function pdu2str($pdu)
    {
        // chop and store bytes
        $number = 0;
        $bitcount = 0;
        $output = '';
        while (strlen($pdu) > 1) {
            $byte = ord(hex2bin(substr($pdu, 0, 2)));
            $pdu = substr($pdu, 2);
            $number += ($byte << $bitcount);
            $bitcount++;
            $output .= chr($number & 0x7F);
            $number >>= 7;
            if (7 == $bitcount) {
                // save extra char
                $output .= chr($number);
                $bitcount = $number = 0;
            }
        }
        return $output;
    }
}

if (!function_exists('baseUrl')) {
    function baseUrl(string $path = '')
    {
        $url = 'http';
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
            $url .= 's';
        }
        $url .= '://';
        $url .= env('APP_URL');

        return implode('/', [$url, trim($path, '/')]);
    }
}

if (!function_exists('viewKlhk')) {
    function viewKlhk($view_non_klhk, $view_klhk = null)
    {
        $view = $view_non_klhk;

        $klhk_backoffice = env('KLHK_BACKOFFICE_URL', 'bupsha.' . env('APP_URL'));

        if ($klhk_backoffice == request()->getHost() && !is_null($view_klhk)) {
            $view = $view_klhk;
        }

        if (is_array($view)) {
            return view(...$view);
        }

        return view($view);
    }
}

if (!function_exists('updateAchievement')) {
    function updateAchievement($company)
    {
        if ($company->categories->count() > 0) {
            $company->achievement_details()
                ->updateExistingPivot(\App\Models\AchievementDetail::whereSlug('business_type')->first()->id,
                    ['achievement_status' => 1]);
        }

        if ($company->short_description !== null):
            $company->achievement_details()
                ->updateExistingPivot(\App\Models\AchievementDetail::whereSlug('about_company')->first()->id,
                    ['achievement_status' => 1]);
        endif;

        if ($company->address_company !== null && $company->id_city !== null && $company->google_place_id !== null):
            $company->achievement_details()
                ->updateExistingPivot(\App\Models\AchievementDetail::whereSlug('address_company')->first()->id,
                    ['achievement_status' => 1]);
        endif;


        if ($company->phone_company !== null && $company->email_company !== null && $company->twitter_company && $company->facebook_company !== null && $company->agent->email != null):
            $company->achievement_details()
                ->updateExistingPivot(\App\Models\AchievementDetail::whereSlug('contact_us')->first()->id,
                    ['achievement_status' => 1]);
        endif;

        if ($company->logo !== null):
            $company->achievement_details()
                ->updateExistingPivot(\App\Models\AchievementDetail::whereSlug('company_logo')->first()->id,
                    ['achievement_status' => 1]);
        endif;

        if ($company->banner !== null):
            $company->achievement_details()
                ->updateExistingPivot(\App\Models\AchievementDetail::whereSlug('company_banner')->first()->id,
                    ['achievement_status' => 1]);
        endif;

        if ($company->title !== null && $company->description !== null && $company->keywords !== null):
            $company->achievement_details()
                ->updateExistingPivot(\App\Models\AchievementDetail::whereSlug('seo')->first()->id,
                    ['achievement_status' => 1]);
        endif;

        if ($company->bank):
            $company->achievement_details()
                ->updateExistingPivot(\App\Models\AchievementDetail::whereSlug('bank_account')->first()->id,
                    ['achievement_status' => 1]);
        endif;
//
//        if ($company->kyc && $company->kyc->status == 'approved'):
//            $company->achievement_details()
//                ->updateExistingPivot(\App\Models\AchievementDetail::whereSlug('kyc')->first()->id,
//                    ['achievement_status' => 1]);
//        endif;
        foreach (\App\Models\Achievement::all() as $item) {
            $check = $company->achievement_details()->whereHas('achievement', function ($a) use ($item) {
                $a->where('achievement_slug', $item->achievement_slug);
            })->where('achievement_status', 0)->first();
            if ($check) {
                $company->achievements()
                    ->updateExistingPivot($item->id,
                        ['company_achievement_status' => 0]);
            } else {
                $company->achievements()
                    ->updateExistingPivot($item->id,
                        ['company_achievement_status' => 1]);
            }
        }
    }
}

if (!function_exists('setPhoneNumber')):
    function setPhoneNumber($phone)
    {
        $arrayPhone = collect(\libphonenumber\PhoneNumberUtil::getInstance()->getSupportedRegions())->except('ID')->prepend('ID')->toArray();
        try {
            $vaPhone = \Propaganistas\LaravelPhone\PhoneNumber::make($phone, $arrayPhone);
            return toObject([
                'ok' => true,
                'phone' => $vaPhone->getPhoneNumberInstance()->getNationalNumber(),
                'phone_country' => $vaPhone->getCountry(),
                'phone_code' => $vaPhone->getPhoneNumberInstance()->getCountryCode()
            ]);
        } catch (Exception $exception) {
            return toObject([
                'ok' => false,
                'data' => null
            ]);
        }
    }
endif;

if (!function_exists('setOrderedUUID')):
    function setOrderedUUID($model)
    {
        $counter = 0;
        $id = randomUID($counter);
        while ($model->find($id)){
            $id = randomUID(++$counter);
        }
        return $id;
    }
endif;

if (!function_exists('randomUID')):
    function randomUID($inc = 0)
    {
        $id = '';
        $time = time();
        $ts = pack('N', $time);
        $m = substr(md5(gethostname()), 0, 3);
        $pid = pack('n', posix_getpid());
        $trail = substr(pack('N', $inc++), 1, 3);
        $bin = sprintf("%s%s%s%s", $ts, $m, $pid, $trail);

        for ($i = 0; $i < 12; $i++) {
            $id .= sprintf("%02X", ord($bin[$i]));
        }
        return $id;
    }
endif;
