<?php

/**
 * Laravel BCA REST API Config.
 *
 * @author     Pribumi Technology
 * @license    MIT
 * @copyright  (c) 2017, Pribumi Technology
 */
return [

    'default'     => 'main',

    'connections' => [

        'main'        => [
            'corp_id'       => '21488f63-19a6-48bd-b6bf-2da57d3c20ed',
            'client_id'     => 'a58b0351-d090-4551-816f-f768ba8fee8a',
            'client_secret' => '593bf8d2-ef60-4986-8c59-2485bb7d7e4f',
            'api_key'       => 'b2949903-91cf-41a3-906f-1c11b086a6e0',
            'secret_key'    => '3393a1ca-fa8f-4f9e-a710-27ccc53ae09a',
            'timezone'      => 'Asia/Jakarta',
            'host'          => 'sandbox.bca.co.id',
            'scheme'        => 'https',
            'development'   => true,
            'options'       => [],
            'port'          => 443,
            'timeout'       => 30,
        ],

        'alternative' => [
            'corp_id'       => 'your-corp_id',
            'client_id'     => 'your-client_id',
            'client_secret' => 'your-client_secret',
            'api_key'       => 'your-api_key',
            'secret_key'    => 'your-secret_key',
            'timezone'      => 'Asia/Jakarta',
            'host'          => 'sandbox.bca.co.id',
            'scheme'        => 'https',
            'development'   => true,
            'options'       => [],
            'port'          => 443,
            'timeout'       => 30,
        ],

    ],

];
