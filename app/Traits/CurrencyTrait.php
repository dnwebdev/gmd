<?php


namespace App\Traits;

trait CurrencyTrait
{
    public function getExchangeRate($amount, $to='IDR', $from = 'USD')
    {

        $available = ['USD', 'IDR', 'EUR'];
        $to = strtoupper($to);
        $from = strtoupper($from);
        if (!in_array($to, $available)) {
            return [
                'success' => false,
                'error' => 'invalid Currency Code'
            ];
        }
        if ($from === $to) {
            return [
                'success' => true,
                'result' => 1 * $amount
            ];
        }

        try {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_URL, 'http://data.fixer.io/api/latest?base=EUR&access_key=05fb0ce6ffa206dba6d51ca342c7049f&symbols=USD,IDR,EUR&format=1');
            $result = curl_exec($curl);
            $result = json_decode($result, true);
            if ($result['success'] === true) {
                $rates = $result['rates'];
                return [
                    'success' => true,
                    'result' => (($rates['EUR'] / $rates[$from]) * $rates[$to]) * $amount
                ];
            }
            return [
                'error' => false,
                'data' => $result
            ];
        } catch (\Exception $exception) {
            return [
                'error' => true,
                'message' => $exception->getMessage()
            ];
        }
    }
}
