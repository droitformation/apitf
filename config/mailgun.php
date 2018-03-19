<?php

return [

    /*
     * API endpoint settings.
     *
     */
    'api' => [
        'endpoint' => 'api.mailgun.net',
        'version' => 'v3',
        'ssl' => true
    ],

    /*
     * Domain name registered with Mailgun
     *
     */
    'domain' =>  env('MAILGUN_DOMAIN'),

    /*
     * Mailgun (private) API key
     *
     */
    'api_key' => env('MAILGUN_SECRET'),

    /*
     * Mailgun public API key
     *
     */
    'public_api_key' => 'pubkey-147824b71b6a0e1cc8cf6f877e6d889d',

];
