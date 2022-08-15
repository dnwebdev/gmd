<?php

namespace App\Traits;
const ONBOARDING_ACTIVITIES = 'https://discordapp.com/api/webhooks/596632635418345473/BC-U59EzBlDE-NWqycodkenTVyU4xyR8E96vNxz023v5bP4kjy7C9is7YWzj6_1_jwKe';
const TRANSACTION_ACTIVITIES = 'https://discordapp.com/api/webhooks/596645035840110592/n1MAOnbP3yP0nOONrecwbSg8jjJs0WoE6XpGWM9k_o7aSFBDgZE757gEQdtbXfHrt5Gc';
const STORE_ACTIVITIES = 'https://discordapp.com/api/webhooks/597056431107080200/FxQIJxfom8gOYy3J4QsZ1PDKWphds1BOemOliXIl2jnekFoWvaySMT6QVWcg-67dQ_en';
const KYC_ACTIVITIES = 'https://discordapp.com/api/webhooks/596668840604336145/xaGq_ZZFVEXqNPs_0eiWT0oAwjID0WgUpchXaVsw6w3meba8OOSogjdR2cX20km7FRvc';
const SUPPORT_ACTIVITIES = 'https://discordapp.com/api/webhooks/597056669687480320/EwLDJtJk_wZqZbZqu_HNieP7slm6HlbBf8PcZBiurFgU_OhfrKB1B1agP9VopSvmcWe2';
const DEFAULT_ACTIVITIES = 'https://discordapp.com/api/webhooks/596604145599774748/-_fGh_a9dUcfnlzgKEeX3WgRqHBxofyrJbC-tvE-Nj1LpeUKgf5IgpuFPA6gtyRzY2Xd';
const PRODUCT_ACTIVITIES = 'https://discordapp.com/api/webhooks/607888345430884373/S1dT6rqZXHzQCsjGV4pf1uGwURZPdSgHoiPtjmNC1QxtxnE_EOeLdtFdMZ_RTzBu-gOj';
const FINANCE_ACTIVITIES = 'https://discordapp.com/api/webhooks/676649164087492618/V-JgXw0oiQqZnYjUQlK3ahY1-cBvmdz4O-VjGwq6eTuX66di5fxrFWWMNSTFXcU38npQ';
const ANALYTICS_ACTIVITIES = 'https://discordapp.com/api/webhooks/710765644332662874/ft_xkUjeUJRnm83mYbuLu2g1P2GPSyp4fbKLXiyurRlA7bVwShsBOdx6YVrI73tzezs1';
trait DiscordTrait
{
    public function sendDiscordNotification($content, $channel, $embed = [])
    {
        $headers = array(
            'Content-Type:application/json'
        );
        $method = "POST";
        $data['content'] = $content;
        if (count($embed) > 0) {
            $data['embeds'] = $embed;
        }

        $data = json_encode($data);
        $url = DEFAULT_ACTIVITIES;
        if (env('APP_ENV', 'production') !== 'local') {
            switch ($channel) {
                case 'onboarding':
                    $url = ONBOARDING_ACTIVITIES;
                    break;
                case 'transaction':
                    $url = TRANSACTION_ACTIVITIES;
                    break;
                case 'store':
                    $url = STORE_ACTIVITIES;
                    break;
                case 'kyc':
                    $url = KYC_ACTIVITIES;
                    break;
                case 'support':
                    $url = SUPPORT_ACTIVITIES;
                    break;
                case 'product':
                    $url = PRODUCT_ACTIVITIES;
                    break;
                case 'finance':
                    $url = FINANCE_ACTIVITIES;
                    break;
                case 'analytics':
                    $url = ANALYTICS_ACTIVITIES;
                    break;
                default:
                    $url = DEFAULT_ACTIVITIES;
            }
        }

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data))
        );
        curl_exec($ch);
        curl_close($ch);
    }
}
