<?php

return [
    'defaults' => [

        /*
         * Add any defaults for the NCCOs for each action here.
         *
         * These will be overwritten by database settings and options passed to the builder.
         * Any value with null will be ignored.
         */
        'actions' => [
            'record' => [],
            'conversation' => [],
            'connect' => [],
            'talk' => [
                'voiceName' => 'Joey',
            ],
            'stream' => [],
            'input' => [],
            'notify' => [],
        ],
    ],

    'number' => env('NEXMO_NUMBER'),
];