<?php
// config/paypal.php

return [
    'client_id' => env('PAYPAL_CLIENT_ID'),
    'client_secret' => env('PAYPAL_CLIENT_SECRET'),
    'settings' => [
        'mode' => env('PAYPAL_MODE', 'sandbox'),
        'http.ConnectionTimeOut' => 30,
        'log.LogEnabled' => true,
    ],
];

?>