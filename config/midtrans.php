<?php

return [
    'serverKey' => env('MIDTRANS_SERVER_KEY'),
    'clientKey' => env('MIDTRANS_CLIENT_KEY'),
    'isProduction' => env('APP_ENV')==='production'?true:false,
    'isSanitized' => env('MIDTRANS_IS_SANITIZED', true),
    'is3ds' => env('MIDTRANS_IS_3DS', true),
];