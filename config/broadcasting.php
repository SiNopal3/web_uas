<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Broadcaster
    |--------------------------------------------------------------------------
    |
    | This option controls the default broadcaster that will be used by the
    | framework when an event needs to be broadcast. You may set this to
    | any of the connections defined in the "connections" array below.
    |
    | Supported: "reverb", "pusher", "ably", "redis", "log", "null"
    |
    */

    'default' => env('BROADCAST_CONNECTION', 'log'),

    /*
    |--------------------------------------------------------------------------
    | Broadcast Connections
    |--------------------------------------------------------------------------
    |
    | Here you may define all of the broadcast connections that will be used
    | to broadcast events to other systems or over WebSockets. Samples of
    | each available type of connection are provided below.
    |
    */

    'connections' => [

        'reverb' => [
            'driver' => 'reverb',
            'key' => env('REVERB_APP_KEY', 'riskintel_reverb_key'),
            'secret' => env('REVERB_APP_SECRET', 'riskintel_reverb_secret'),
            'app_id' => env('REVERB_APP_ID', 'riskintel_app'),
            'options' => [
                'host' => env('REVERB_SERVER_HOST', '127.0.0.1'),
                'port' => (int) env('REVERB_SERVER_PORT', 8080),
                'scheme' => env('REVERB_SERVER_SCHEME', 'http'),
                'useTLS' => env('REVERB_SERVER_SCHEME', 'http') === 'https',
            ],
            'client_options' => [
                // Allow Guzzle options when communicating with Reverb server
            ],
        ],

        'pusher' => [
            'driver' => 'pusher',
            'key' => env('PUSHER_APP_KEY', 'riskintel_key'),
            'secret' => env('PUSHER_APP_SECRET', 'riskintel_secret'),
            'app_id' => env('PUSHER_APP_ID', 'riskintel_app'),
            'options' => [
                'cluster' => env('PUSHER_APP_CLUSTER', 'mt1'),
                'host' => env('PUSHER_HOST') ?: 'api-'.env('PUSHER_APP_CLUSTER', 'mt1').'.pusher.com',
                'port' => env('PUSHER_PORT', 443),
                'scheme' => env('PUSHER_SCHEME', 'https'),
                'encrypted' => true,
                'useTLS' => env('PUSHER_SCHEME', 'https') === 'https',
            ],
        ],

        'log' => [
            'driver' => 'log',
        ],

        'null' => [
            'driver' => 'null',
        ],

    ],

];
