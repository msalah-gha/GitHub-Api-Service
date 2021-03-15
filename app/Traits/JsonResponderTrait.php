<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait JsonResponderTrait
{
    /**
     * This Method use to return a Json Response.
     *
     * @param bool $status
     * @param string $message
     * @param array  $errors
     * @param array  $data
     * @param int    $serverStatusCode
     *
     * @return JsonResponse
     */
    public function jsonResponse(bool $status, string $message, $errors = [], $data = [], $serverStatusCode = 200): JsonResponse
    {
        return response()->json(
            [
                'status'  => $status,
                'message' => $message,
                'errors'  => (is_array($errors)) ? $errors : [$errors],
                'data'    => $data,
            ],
            $serverStatusCode
        );
    }

    /**
     * Return Success Response.
     *
     * @param array $data
     *
     * @return JsonResponse
     */
    public function successResponse($data = []): JsonResponse
    {
        return $this->jsonResponse(true, 'Success', [], $data);
    }

    /**
     * Return Validation error Response.
     *
     * @param $errors
     *
     * @return JsonResponse
     */
    public function validationErrorResponse($errors): JsonResponse
    {
        return $this->jsonResponse(
            false,
            'validation_error',
            $errors,
            [],
            422
        );
    }

    /**
     * Return Not Found Response.
     *
     * @param $message
     *
     * @return JsonResponse
     */
    public function badRequestResponse($message = ''): JsonResponse
    {
        $message = $message ? $message : 'Bad Request';
        return $this->jsonResponse(false, $message, [], [], 400);
    }
}