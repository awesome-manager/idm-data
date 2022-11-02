<?php

return [
    'defaults' => [
        'guard' => 'idm',
    ],

    'guards' => [
        'idm' => [
            'driver' => 'idm',
            'provider' => 'users',
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'idm_users',
            'model' => App\Models\User::class,
        ],
    ]
];
