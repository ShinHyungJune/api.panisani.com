<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'naver' => [
        'client_id' => env('NAVER_CLIENT_ID'),
        'client_secret' => env('NAVER_CLIENT_SECRET'),
        'redirect' => env('NAVER_REDIRECT_URI')
    ],

    'naverCustom' => [
        'client_id' => env('NAVER_CLIENT_ID', "duaxYdtz0yxSw8K3O7HT"),
        'client_secret' => env('NAVER_CLIENT_SECRET', "SP6lZUNGw1"),
        'redirect' => env('NAVER_REDIRECT_URI',"/login/naverCustom")
    ],

    'kakao' => [
        'client_id' => env('KAKAO_CLIENT_ID'),
        'client_secret' => env('KAKAO_CLIENT_SECRET', ),
        'redirect' => env('KAKAO_REDIRECT_URI', )
    ],

    'kakaoCustom' => [
        'client_id' => env('KAKAO_CLIENT_ID', "e5cfb51f4a1ad5db893377e70e3e616f"),
        'client_secret' => env('KAKAO_CLIENT_SECRET', "RgYVbgtgp9adfEulxzAs0KpSg9QJXLfo"),
        'redirect' => env('KAKAO_REDIRECT_URI', "/login/kakaoCustom")
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID', "126671940317-38k3r4fn46494lmjlttu0tor7ehbphbv.apps.googleusercontent.com"),
        'client_secret' => env('GOOGLE_CLIENT_SECRET', "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"),
        'redirect' => env('GOOGLE_REDIRECT_URI', "/login/google")
    ],

    'facebook' => [
        'client_id' => env('FACEBOOK_CLIENT_ID', "2502926093182098"),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET', "36c4428d72f72867945d4d31a6f2fde6"),
        'redirect' => env('FACEBOOK_REDIRECT_URI', "/login/facebook")
    ]
];
