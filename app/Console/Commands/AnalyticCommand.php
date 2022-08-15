<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Analytics\AnalyticsFacade as Analytics;
use Spatie\Analytics\Period;

class AnalyticCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'analytic:get';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $topPage = Analytics::fetchMostVisitedPages(Period::days(1),10);
        $domain = 'http://support.mygomodo.com';
        $lastWeek = \Carbon\Carbon::now()->subDay()->toDateString();
        $now = \Carbon\Carbon::now()->toDateString();
        $msg[] = "**TOP PAGE VIEWS $domain PERIODE $lastWeek - $now**";
        $msg[] = "```";
        foreach ($topPage as $top) {
            $msg[] = "---------------------------------";
//            $msg[] = "Page Title  : " . $top['pageTitle'];
            $msg[] = "Page URL    : " . $domain . $top['url'];
            $msg[] = "Page Views  : " . $top['pageViews'];
        }
        $msg[] = "```";
        $msg[] = "\n";
        $msg[] = "\n";
        $this->sendDiscord($msg);
        $analitycs = Analytics::fetchVisitorsAndPageViews(Period::days(1));
        $views = [];
        foreach ($analitycs as $index => $analityc):
            $curent = [
                'date' => $analityc['date']->format('d M Y'),
                'pageTitle' => $analityc['pageTitle'],
                'visitors' => $analityc['visitors'],
                'pageViews' => $analityc['pageViews']
            ];
            $views[$analityc['date']->format('d M Y')][] = $curent;
        endforeach;


        $msg = [];
        $msg[] = "**HISTORY PAGE VIEWS $domain PERIODE $lastWeek - $now**";
        $msg[] = "```";
        foreach ($views as $view => $details) {
            $msg[] = "---------------------------------";
            $msg[] = "$view";
            $msg[] = "---------------------------------";
            foreach ($details as $detail):
                $msg[] = "Page Title  : " . $detail['pageTitle'];
                $msg[] = "Page Views  : " . $detail['pageViews'];
                $msg[] = "Visitors    : " . $detail['visitors'];
                $msg[] = "\n";
            endforeach;
        }
        $msg[] = "```";
        $msg[] = "\n";
        $msg[] = "\n";
        $this->sendDiscord($msg);
        $msg = [];
        $topBrowser = Analytics::fetchTopBrowsers(Period::days(1));
        $msg[] = "**TOP BROWSER**";
        $msg[] = "```";
        foreach ($topBrowser as $view) {
            $msg[] = "---------------------------------";
            $msg[] = "Browser     : " . $view['browser'];
            $msg[] = "Sessions    : " . $view['sessions'];
        }
        $msg[] = "```";
        $msg[] = "\n";
        $msg[] = "\n";
        $this->sendDiscord($msg);

    }

    private function sendDiscord(array $msg){
        $headers = array(
            'Content-Type:application/json'
        );
        $method = "POST";
        $data['content'] = sprintf('%s', implode("\n", $msg));
        $data = json_encode($data);
        $url = 'https://discordapp.com/api/webhooks/710765644332662874/ft_xkUjeUJRnm83mYbuLu2g1P2GPSyp4fbKLXiyurRlA7bVwShsBOdx6YVrI73tzezs1';
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
