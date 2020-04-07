<?php


namespace TaylorNetwork\LaravelNexmo\Controllers\Api;

use TaylorNetwork\LaravelNexmo\Controllers\Controller;
use Exception;

abstract class ApiController extends Controller
{
    public function prepareExceptionResponse(Exception $exception)
    {
        if(config('app.debug', false) && config('app.env', 'production') === 'local') {
            return response($exception->getMessage(), 500);
        }
        return response('Unexpected Error', 500);
    }
}