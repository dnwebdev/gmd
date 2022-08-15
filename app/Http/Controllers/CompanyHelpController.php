<?php

namespace App\Http\Controllers;

use App\Traits\DiscordTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendEmail;
use League\HTMLToMarkdown\HtmlConverter;

class CompanyHelpController extends Controller
{
    use DiscordTrait;
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
        $request['my_company'] = $this->company;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->initalize($request);
        $instance = \App\Models\Company::find($this->company);
        if (auth()->user()->company->is_klhk == 1){
            return view('klhk.dashboard.company.help.index');
        }
        return view('dashboard.company.help.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param mixed $request
     *
     * @return void
     */
    public function store(\App\Http\Requests\HelpFormRequest $request)
    {
        $this->initalize($request);
        $company = \App\Models\Company::find($this->company);
        $title = $request->get('title');
        $name = $company->company_name;
        $domain = $company->domain_memoria;
        $email = $company->email_company;
        $phone = $company->phone_company;
        $user_detail = 'Company Name: ' . $name . '. Domain: ' . $domain . '. Email: ' . $email;
        $message = clean($request->get('message'), 'simple') . '. User Details: ' . $user_detail;
        $subject = "FEEDBACK: " . $title;
        $to = "support@mygomodo.com";
        $utility = app('\App\Services\UtilityService');

        if ($request->hasFile('screenshot')) {
            $screenshot = $request->file('screenshot');
            $filename = time() . '-' . implode('', explode(' ', $screenshot->getClientOriginalName()));
            $screenshot->move('uploads/help/', $filename);

            $data = [
                'company_name' => $name,
                'company_domain' => $domain,
                'company_email' => $email,
                'company_phone' => $phone,
                'message' => $request->get('message'),
                'screenshot' => 'uploads/help/' . $filename,
            ];
            if (auth()->user()->company->is_klhk == 1){
                $template = view('klhk.dashboard.company.help.mail', $data)->render();
            }
            $template = view('dashboard.company.help.mail', $data)->render();


            $ip = isset($_SERVER['HTTP_CF_CONNECTING_IP']) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : $request->ip();
            $loc = \Stevebauman\Location\Facades\Location::get($ip);
            $http = env('HTTPS', false) == true ? 'https://' : 'http://';
            $convert = new HtmlConverter();
            $contentTitle = '**Support Request ' . Carbon::now()->format('d M Y H:i:s') . '**';
            $content = '<br><p>Hai, salah satu teman provider membutuhkan bantuan kita</p>';

            if (auth()->user()->company->is_klhk == 1){
                $template = view('klhk.dashboard.company.help.mail', $data)->render();
            }
            $template = view('dashboard.company.help.mail', $data)->render();
            $content .= '<strong>Berikut adalah Pesannya</strong><br>';
            $content .= '<h1>Judul :' . $title . '</h1>';
            $content .= $request->get('message');
            $content .= '<p><strong>Provided by :</strong></p>';
            $content = $convert->convert($content);
            $content .= '```';
            $content .= 'Company Name : ' . $name . '
Domain Gomodo : ' . $http . $domain . '
Email Company : ' . $email . '
IP Address : ' . $ip . '
City name : ' . $loc->cityName . '
Region Name : ' . $loc->regionName . '
Country Code : ' . $loc->countryCode;
            $content .= '```';
            $content .= '=======================================================';
            $embed = [[
                'title' => 'Lampiran',
                "image" => [
//                    "url"=>"https://i.imgur.com/ZGPxFN2.jpg"
                    "url" => asset('uploads/help/' . $filename)
                ]]];

            $this->sendDiscordNotification($contentTitle . $content, 'support', $embed);
            dispatch(new SendEmail($subject, $to, $template));
            return json_encode(['status' => 200, 'message' => \trans('help_provider.help_request_submitted')]);
        } else {
            $data = [
                'company_name' => $name,
                'company_domain' => $domain,
                'company_email' => $email,
                'company_phone' => $phone,
                'message' => $request->get('message'),
                'screenshot' => '',
            ];
            $ip = isset($_SERVER['HTTP_CF_CONNECTING_IP']) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : $request->ip();
            $loc = \Stevebauman\Location\Facades\Location::get($ip);
            $http = env('HTTPS', false) == true ? 'https://' : 'http://';
            $convert = new HtmlConverter();
            $contentTitle = '**Support Request ' . Carbon::now()->format('d M Y H:i:s') . '**';
            $content = '<br><p>Hai, salah satu teman provider membutuhkan bantuan kita</p>';
            if (auth()->user()->company->is_klhk == 1){
                $template = view('klhk.dashboard.company.help.mail', $data)->render();
            }
            $template = view('dashboard.company.help.mail', $data)->render();
            $content .= '<strong>Berikut adalah Pesannya</strong><br>';
            $content .= 'Judul :' . $title . '<br>';
            $content .= $request->get('message');
            $content .= '<p><strong>Provided by :</strong></p>';
            $content = $convert->convert($content);
            $content .= '```';
            $content .= 'Company Name : ' . $name . '
Domain Gomodo : ' . $http . $domain . '
Email Company : ' . $email . '
IP Address : ' . $ip . '
City name : ' . $loc->cityName . '
Region Name : ' . $loc->regionName . '
Country Code : ' . $loc->countryCode;
            $content .= '```';
            $content .= '=======================================================';

            dispatch(new SendEmail($subject, $to, $template));
            $this->sendDiscordNotification($contentTitle . $content, 'support');
            return json_encode(['status' => 200, 'message' => \trans('help_provider.help_request_submitted')]);
        }
    }
}