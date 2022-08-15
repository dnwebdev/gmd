<?php


namespace Gomodo\Discord\activities;
use Gomodo\Discord\BaseDiscord;

class Onboarding extends BaseDiscord
{
    public function __construct($url)
    {
        $this->url = $url;
    }
}
