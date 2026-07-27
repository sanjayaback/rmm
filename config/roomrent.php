<?php

return [
    'khalti_public_key'  => env('KHALTI_PUBLIC_KEY', ''),
    'khalti_secret_key'  => env('KHALTI_SECRET_KEY', ''),
    'khalti_base_url'    => env('KHALTI_BASE_URL', 'https://a.khalti.com/api/v2'),
    'khalti_fake_mode'   => env('KHALTI_FAKE_MODE', true),
    'default_unlock_fee' => env('DEFAULT_UNLOCK_FEE', 50),
    'referral_reward'    => env('REFERRAL_REWARD', 25),
    'map_default_lat'    => env('MAP_DEFAULT_LAT', 27.7172),
    'map_default_lng'    => env('MAP_DEFAULT_LNG', 85.3240),
    'map_default_zoom'   => env('MAP_DEFAULT_ZOOM', 13),
];
