<?php

namespace App\Traits;

use CodeIgniter\HTTP\ResponseInterface;

trait ApiResponseTrait
{
    /**
     * Success response with data
     */
    protected function apiSuccess($data = [], string $message = 'Success', int $code = 200)
    {
        return $this->respond([
            'meta' => [
                'code' => $code,
                'message' => $message
            ],
            'data' => $data
        ], $code);
    }

    /**
     * Created response
     */
    protected function apiCreated($data = [], string $message = 'Data created successfully')
    {
        return $this->apiSuccess($data, $message, 201);
    }

    /**
     * Error response
     */
    protected function apiError(string $message = 'An error occurred', int $code = 400, $errors = null)
    {
        $response = [
            'meta' => [
                'code' => $code,
                'message' => $message
            ]
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        return $this->respond($response, $code);
    }

    /**
     * Validation error response
     */
    protected function apiValidationError($errors, string $message = 'Validation failed')
    {
        return $this->apiError($message, 422, $errors);
    }

    /**
     * Not found response
     */
    protected function apiNotFound(string $message = 'Data not found')
    {
        return $this->apiError($message, 404);
    }

    /**
     * Server error response
     */
    protected function apiServerError(string $message = 'Internal server error')
    {
        return $this->apiError($message, 500);
    }
}
