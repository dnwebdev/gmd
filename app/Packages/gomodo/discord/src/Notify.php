<?php

namespace Gomodo\Discord;

use Gomodo\Discord\activities\Onboarding;

class Notify
{
    public static function onboard()
    {
        $url = env('DEFAULT_DISCORD_WEBHOOK', 'https://discordapp.com/api/webhooks/596604145599774748/-_fGh_a9dUcfnlzgKEeX3WgRqHBxofyrJbC-tvE-Nj1LpeUKgf5IgpuFPA6gtyRzY2Xd');
        $url = env('ONBOARDING_DISCORD_WEBHOOK', $url);
        return new Onboarding($url);
    }

    public static function product()
    {
        $url = env('DEFAULT_DISCORD_WEBHOOK', 'https://discordapp.com/api/webhooks/596604145599774748/-_fGh_a9dUcfnlzgKEeX3WgRqHBxofyrJbC-tvE-Nj1LpeUKgf5IgpuFPA6gtyRzY2Xd');
        $url = env('PRODUCT_DISCORD_WEBHOOK', $url);
        return new Onboarding($url);
    }

    public static function support()
    {
        $url = env('DEFAULT_DISCORD_WEBHOOK', 'https://discordapp.com/api/webhooks/596604145599774748/-_fGh_a9dUcfnlzgKEeX3WgRqHBxofyrJbC-tvE-Nj1LpeUKgf5IgpuFPA6gtyRzY2Xd');
        $url = env('SUPPORT_DISCORD_WEBHOOK', $url);
        return new Onboarding($url);
    }

    public static function transaction()
    {
        $url = env('DEFAULT_DISCORD_WEBHOOK', 'https://discordapp.com/api/webhooks/596604145599774748/-_fGh_a9dUcfnlzgKEeX3WgRqHBxofyrJbC-tvE-Nj1LpeUKgf5IgpuFPA6gtyRzY2Xd');
        $url = env('TRANSACTION_DISCORD_WEBHOOK', $url);
        return new Onboarding($url);
    }

    public static function location()
    {
        $url = env('DEFAULT_DISCORD_WEBHOOK', 'https://discordapp.com/api/webhooks/596604145599774748/-_fGh_a9dUcfnlzgKEeX3WgRqHBxofyrJbC-tvE-Nj1LpeUKgf5IgpuFPA6gtyRzY2Xd');
        $url = env('GOOGLE_DISCORD_WEBHOOK', $url);
        return new Onboarding($url);
    }
}
