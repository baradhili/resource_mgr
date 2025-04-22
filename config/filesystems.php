<?php

return [

    'disks' => [
        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
            'throw' => false,
        ],

        'samlidp' => [
            'driver' => 'local',
            'root' => storage_path().'/samlidp',
        ],
    ],

];
