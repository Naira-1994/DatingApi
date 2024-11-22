<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function response(array $data = [], int $statusCode = ResponseAlias::HTTP_OK): JsonResponse
    {
        return response()->json($data, $statusCode);
    }

    public function responseSuccess(array $data = [], $success = true): JsonResponse
    {
        return response()->json(['data' => $data, 'success' => $success]);
    }

    public function responseNoContent(): JsonResponse
    {
        return response()->json([], ResponseAlias::HTTP_NO_CONTENT);
    }
}
