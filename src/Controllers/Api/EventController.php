<?php


namespace TaylorNetwork\LaravelNexmo\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use TaylorNetwork\LaravelNexmo\Models\Call;

class EventController extends ApiController
{
    public function handleEventUpdate(Request $request)
    {
        try {
            Call::handleEventUpdate($request);
        } catch(Exception $exception) {
            if(config('app.debug', false) && config('app.env', 'production') === 'local') {
                return response($exception->getMessage(), 500);
            }
            return response('Unexpected Error', 500);
        }
        return response('OK', 200);
    }
}