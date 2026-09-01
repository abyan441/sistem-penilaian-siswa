<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

/**
 * Base controller untuk API responses.
 * Menyediakan helper methods untuk response yang konsisten di seluruh aplikasi.
 */
class ApiController extends Controller
{
    /**
     * Return success JSON response.
     *
     * @param mixed $data The response data
     * @param string $message Success message (optional)
        * @param int $status HTTP status code (default: 200)
        * @param array $meta Additional top-level response fields
     * @return JsonResponse
     */
    protected function successResponse($data = null, string $message = '', int $status = 200, array $meta = []): JsonResponse
    {
        $response = array_merge(['success' => true], $meta);

        if ($message !== '') {
            $response['message'] = $message;
        }

        if ($data !== null) {
            $response['data'] = $data;
        }

        return response()->json($response, $status);
    }

    /**
     * Return error JSON response.
     *
     * @param string $message Error message
     * @param int $status HTTP status code (default: 422)
     * @return JsonResponse
     */
    protected function errorResponse(string $message, int $status = 422): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], $status);
    }

    /**
     * Return error response when resource not found.
     *
     * @param string $resource Resource name (default: 'Sumber daya')
     * @return JsonResponse
     */
    protected function notFoundResponse(string $resource = 'Sumber daya'): JsonResponse
    {
        return $this->errorResponse("{$resource} tidak ditemukan.", 404);
    }
}
