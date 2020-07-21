<?php

return [
    //Root path of the project
    'base_path'   => [
        'driver' => 'local',
        'root'   => base_path(),
    ],

    //MIGRATIONS
    'migrations'  => [
        'driver' => 'local',
        'root'   => database_path(DIRECTORY_SEPARATOR . 'migrations' . DIRECTORY_SEPARATOR),
    ],

    //MODELS
    'models'      => [
        'driver' => 'local',
        'root'   => app_path() . DIRECTORY_SEPARATOR . 'Models' . DIRECTORY_SEPARATOR,
    ],

    //CONTROLLERS
    'controllers' => [
        'driver' => 'local',
        'root'   => app_path() . DIRECTORY_SEPARATOR . 'Http' . DIRECTORY_SEPARATOR . 'Controllers'
            . DIRECTORY_SEPARATOR . 'API' . DIRECTORY_SEPARATOR,
    ],

    //ROUTES
    'routes'      => [
        'driver' => 'local',
        'root'   => base_path() . DIRECTORY_SEPARATOR . 'routes' . DIRECTORY_SEPARATOR
            . 'zzgo' . DIRECTORY_SEPARATOR,
    ],

    //RESOURCES
    'resources'   => [
        'driver' => 'local',
        'root'   => app_path() . DIRECTORY_SEPARATOR . 'Http' . DIRECTORY_SEPARATOR
            . 'Resources' . DIRECTORY_SEPARATOR,
    ],
];
