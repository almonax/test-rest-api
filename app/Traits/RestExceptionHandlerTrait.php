<?php

namespace App\Traits;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Prophecy\Exception\Doubler\MethodNotFoundException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

trait RestExceptionHandlerTrait
{
    /**
     * Creates a new JSON response based on exception type.
     *
     * @param Request $request
     * @param Exception $e
     * @return \Illuminate\Http\JsonResponse
     */
    protected function getJsonResponseForException(Request $request, Exception $e)
    {
        switch(true) {
            case $this->isModelNotFoundException($e):
                $exceptionMethod = $this->modelNotFound($e->getMessage());
                break;
            case $this->isMethodNotFoundException($e):
                $exceptionMethod = $this->methodNotFound();
                break;
            case $this->isMethodNotAllowedHttpException($e):
                $exceptionMethod = $this->methodNotAllowed();
                break;
            default:
                $exceptionMethod = $this->badRequest();
        }
        return $exceptionMethod;
    }

    /**
     * Determines if the given exception is an model not found.
     *
     * @param Exception $e
     * @return bool
     */
    protected function isModelNotFoundException(Exception $e)
    {
        return $e instanceof ModelNotFoundException;
    }

    /**
     * Determines if the given exception is an method not found.
     *
     * @param Exception $e
     * @return bool
     */
    protected function isMethodNotFoundException(Exception $e)
    {
        return $e instanceof MethodNotFoundException;
    }

    /**
     * Determines if the given exception is an method now allowed.
     *
     * @param Exception $e
     * @return bool
     */
    protected function isMethodNotAllowedHttpException(Exception $e)
    {
        return $e instanceof MethodNotAllowedHttpException;
    }

    /**
     * Returns json response.
     *
     * @param array|null $payload
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function jsonResponse(array $payload = [], $statusCode = 404)
    {
        $payload = $payload ?: [];

        return response()->json($payload, $statusCode);
    }

    /**
     * Returns json response for generic bad request.
     *
     * @param string $message
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function badRequest($message = 'Bad request', $statusCode = 400)
    {
        return $this->jsonResponse([
            'code' => $statusCode,
            'success' => false,
            'message' => $message
        ], $statusCode);
    }

    /**
     * Returns json response for Method not found exception
     *
     * @param string $message
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function methodNotFound($message = 'Method not found', $statusCode = 404)
    {
        return $this->jsonResponse([
            'code' => $statusCode,
            'success' => false,
            'message' => $message
        ], $statusCode);
    }

    /**
     * Returns json response for Eloquent model not found exception
     *
     * @param string $message
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function modelNotFound($message = 'Model not found', $statusCode = 404)
    {
        return $this->jsonResponse([
            'code' => $statusCode,
            'success' => false,
            'message' => $message
        ], $statusCode);
    }

    /**
     * Returns json response for Method not allowed
     *
     * @param string $message
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function methodNotAllowed($message = 'Method not allowed', $statusCode = 405)
    {
        return $this->jsonResponse([
            'code' => $statusCode,
            'success' => false,
            'message' => $message
        ], $statusCode);
    }
}