<?php

return [
    'serverKey' => env('MIDTRANS_SERVER_KEY', 'SB-Mid-server-RtBZdlPClrcYRblI5tCE95J5'),
    'clientKey' => env('MIDTRANS_CLIENT_KEY', 'SB-Mid-client-M8cw8nlLX4T6fhX8'),
    'isProduction' => env('APP_ENV'),
    'isSanitized' => env('MIDTRANS_IS_SANITIZED', true),
    'is3ds' => env('MIDTRANS_IS_3DS', true),
];