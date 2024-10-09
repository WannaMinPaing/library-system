<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait ResponseHelper
{
    protected function responseSucceed($data, string $message = 'Success', $code = Response::HTTP_OK): JsonResponse
    {
        $response = response()->json([
            'status' => true,
            'code' => $code,
            'message' => $message,
            'data' => $data,
        ], $code);

        return $response;
    }

    protected function responseFailed(string $message = 'Failed', $code = Response::HTTP_UNAUTHORIZED): JsonResponse
    {
        $response = response()->json([
            'status' => false,
            'code' => $code,
            'message' => $message,
        ], $code);

        return $response;
    }
}
