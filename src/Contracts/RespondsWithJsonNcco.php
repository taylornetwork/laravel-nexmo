<?php


namespace TaylorNetwork\LaravelNexmo\Contracts;


use Illuminate\Http\JsonResponse;

interface RespondsWithJsonNcco
{
    /**
     * Send a JSON NCCO response
     *
     * @param int $httpStatus
     * @return JsonResponse
     */
    public function respond(int $httpStatus = 200): JsonResponse;

    /**
     * Build the JSON Response
     *
     * @param int $httpStatus
     * @return JsonResponse
     */
    public function buildResponse(int $httpStatus = 200): JsonResponse;
}