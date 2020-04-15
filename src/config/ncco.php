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

    /*
     * Nexmo Number (LVN)
     */
    'number' => env('NEXMO_NUMBER'),

    /*
     * Controller namespace
     */
    'controller_namespace' => 'App\\Http\\Controllers\\',

    /*
     * Namespace for your API controllers
     */
    'api_controller_namespace' => 'App\\Http\\Controllers\\Api\\',

    /*
     * Your app's call controller, if it can't be found with this alone, it will check in
     * the api_controller_namespace
     */
    'call_controller_class' => 'CallController',

    /*
     * Your app's answer method, it will be called as
     *    $yourAppControllerClass->answer($request);
     *
     * It should return a JSON NCCO
     */
    'answer_method' => 'answer',
];