<?php

 return [
    'defaults' => [
        'guard' => 'api',
        'passwords' => 'users',
    ],

    'guards' => [

        'api' => [
            'driver' => 'jwt',
            'provider' => 'users',
        ],

        'admin' => [
            'driver' => 'jwt',
            'provider' => 'users',
        ],
        'user' => [
            'driver' => 'jwt',
            'provider' => 'users',
        ],
        
    ],

    'providers' => [
        
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],
    ],

    'aliases' => [
        'JWTAuth'=>Tymon\JWTAuth\Facades\JWTAuth::class,
    ],
];