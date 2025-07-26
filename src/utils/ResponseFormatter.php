<?php

namespace App\Utils;

/**
 * Response Formatter Utility
 * Standardizes API responses across the application
 */
class ResponseFormatter
{
    /**
     * Format successful response
     */
    public static function success($data = null, string $message = 'Success', int $code = 200): array
    {
        return [
            'success' => true,
            'message' => $message,
            'data' => $data,
            'code' => $code,
            'timestamp' => date('c')
        ];
    }

    /**
     * Format error response
     */
    public static function error(string $message = 'An error occurred', int $code = 400, $errors = null): array
    {
        return [
            'success' => false,
            'message' => $message,
            'errors' => $errors,
            'code' => $code,
            'timestamp' => date('c')
        ];
    }

    /**
     * Format paginated response
     */
    public static function paginated($data, int $total, int $page, int $perPage, string $message = 'Data retrieved'): array
    {
        return [
            'success' => true,
            'message' => $message,
            'data' => $data,
            'pagination' => [
                'total' => $total,
                'page' => $page,
                'per_page' => $perPage,
                'total_pages' => ceil($total / $perPage),
                'has_next' => $page < ceil($total / $perPage),
                'has_prev' => $page > 1
            ],
            'timestamp' => date('c')
        ];
    }

    /**
     * Format validation error response
     */
    public static function validationError(array $errors): array
    {
        return self::error('Validation failed', 422, $errors);
    }

    /**
     * Format not found response
     */
    public static function notFound(string $resource = 'Resource'): array
    {
        return self::error("{$resource} not found", 404);
    }

    /**
     * Format unauthorized response
     */
    public static function unauthorized(string $message = 'Unauthorized access'): array
    {
        return self::error($message, 401);
    }

    /**
     * Format server error response
     */
    public static function serverError(string $message = 'Internal server error'): array
    {
        return self::error($message, 500);
    }
}
