<?php

namespace Kayiz;
class Woowa
{
    public static function SendMessageSync()
    {
        return new SendMessageSync();
    }
    public static function SendMessageAsync()
    {
        return new SendMessageAsync();
    }

    public static function SendImageSync()
    {
        return new SendImageSync();
    }
    public static function SendImageAsync()
    {
        return new SendImageAsync();
    }

    public static function SendFileSync()
    {
        return new SendFileSync();
    }

    public static function SendFileAsync()
    {
        return new SendFileAsync();
    }

    public static function CheckNumber()
    {
        return new CheckPhoneWhatsapp();
    }
}
