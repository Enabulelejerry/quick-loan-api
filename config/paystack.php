<?php


return [
    'publicKey' => env('PAYSTACK_PUBLIC_KEY'),
    'secretKey' => env('PAYSTACK_SECRET_KEY'),
    'paymentUrl' => 'https://api.paystack.co',
    'merchantEmail' => env('MERCHANT_EMAIL', 'your@email.com'),
];
