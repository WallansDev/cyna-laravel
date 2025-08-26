<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Stripe Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration pour l'intégration Stripe
    |
    */

    'publishable_key' => env('STRIPE_PUBLISHABLE_KEY', ''),
    'secret_key' => env('STRIPE_SECRET_KEY', ''),
    'webhook_secret' => env('STRIPE_WEBHOOK_SECRET', ''),

    /*
    |--------------------------------------------------------------------------
    | Configuration par défaut
    |--------------------------------------------------------------------------
    */

    'currency' => env('STRIPE_CURRENCY', 'eur'),
    'default_amount' => env('STRIPE_DEFAULT_AMOUNT', 2000), // en centimes

    /*
    |--------------------------------------------------------------------------
    | Configuration des webhooks
    |--------------------------------------------------------------------------
    */

    'webhook_events' => [
        'payment_intent.succeeded',
        'payment_intent.payment_failed',
        'payment_intent.canceled',
        'charge.succeeded',
        'charge.failed',
    ],
]; 