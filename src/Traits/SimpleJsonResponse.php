<?php


namespace TaylorNetwork\LaravelNexmo\Traits;


use Illuminate\Http\JsonResponse;
use Exception;

/**
 * Trait SimpleJsonResponse
 *
 * Use this to implement RespondsWithJsonNcco if the respond method doesn't do anything extra.
 *
 * @package TaylorNetwork\LaravelNexmo\Traits
 */
trait SimpleJsonResponse
{
    /**
     * @inheritDoc
     */
    public function respond(int $httpStatus = 200): JsonResponse
    {
        return $this->buildResponse($httpStatus);
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function buildResponse(int $httpStatus = 200): JsonResponse
    {
        throw new Exception(get_class() . ' must implement buildResponse()');
    }
}