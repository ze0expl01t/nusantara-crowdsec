<?php

return [
    'lapi_url'         => env('CROWDSEC_LAPI_URL', 'http://127.0.0.1:8080'),
    'bouncer_key'      => env('CROWDSEC_BOUNCER_KEY'),
    'machine_name'     => env('CROWDSEC_MACHINE_NAME'),
    'machine_password' => env('CROWDSEC_MACHINE_PASSWORD'),
    'audit_enabled'    => env('CROWDSEC_AUDIT_ENABLED', true),
];
