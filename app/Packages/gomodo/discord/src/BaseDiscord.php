<?php

namespace Gomodo\Discord;
abstract class BaseDiscord
{
    protected $url;
    protected $method = "POST";
    protected $content;
    protected $embeds = [];

    public function send()
    {
        $data['content'] = $this->content;
        if (count($this->embeds) > 0) {
            $data['embeds'] = $this->embeds;
        }
        $data = json_encode($data);

        $ch = curl_init($this->url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data)]);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $this->method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        curl_close($ch);
    }

    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    public function setEmbeds(array $embeds=[])
    {
        $this->embeds = $embeds;
        return $this;
    }
}
