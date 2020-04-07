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
        } catch (Exception $exception) {
            return $this->prepareExceptionResponse($exception);
        }
        return response('OK', 200);

    }
}