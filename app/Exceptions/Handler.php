<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        if (Str::contains($e->getMessage(), 'Duplicate entry')) {
            return response()->json([
                'message' => 'Duplicate entry',
                'errors' => $e->getMessage()
            ], 400);
        }

        $httpCode = $e->getCode();
        $statusCode = $e->getCode();
        $details = [
            'message' => $e->getMessage(),
        ];
        if ($e instanceof ValidationException) {
            $httpCode = ResponseAlias::HTTP_UNPROCESSABLE_ENTITY;
            $statusCode = BusinessLogicException::VALIDATION_FAILED;
            $details['message'] = $e->getMessage();
            foreach ($e->errors() as $key => $error) {
                $details['errors'][$key] = $error[0] ?? 'Unknown error';
            }
        }

        if ($e instanceof ValidationFailedException) {
            $httpCode = ResponseAlias::HTTP_UNPROCESSABLE_ENTITY;
            $statusCode = BusinessLogicException::VALIDATION_FAILED;
            $details['message'] = $e->getMessage();
        }

        if ($e instanceof NotFoundHttpException || $e instanceof ModelNotFoundException) {
            $httpCode = ResponseAlias::HTTP_NOT_FOUND;
            $statusCode = ResponseAlias::HTTP_NOT_FOUND;
            $details['message'] = 'Not Found';
        }

        if ($e instanceof BadRequestHttpException) {
            $httpCode = ResponseAlias::HTTP_BAD_REQUEST;
            $statusCode = ResponseAlias::HTTP_BAD_REQUEST;
            $details['message'] = 'Bad Request';
        }

        if ($e instanceof MethodNotAllowedHttpException) {
            $httpCode = ResponseAlias::HTTP_METHOD_NOT_ALLOWED;
            $statusCode = ResponseAlias::HTTP_METHOD_NOT_ALLOWED;
            $details['message'] = 'Method Not Allowed';
        }

        if ($e instanceof AuthenticationException) {
            $httpCode = ResponseAlias::HTTP_UNAUTHORIZED;
            $statusCode = ResponseAlias::HTTP_UNAUTHORIZED;
            $details['message'] = 'Unauthorized';
        }

        if ($e instanceof AuthorizationException) {
            $httpCode = ResponseAlias::HTTP_FORBIDDEN;
            $statusCode = ResponseAlias::HTTP_FORBIDDEN;
            $details['message'] = $e->getMessage();
        }

        if ($e instanceof BusinessLogicException) {
            $httpCode = $e->getHttpStatusCode();
            $statusCode = $e->getStatus();
            $details['message'] = $e->getStatusMessage();
        }

        $data = [
            'status' => $statusCode,
            'errors' => $details,
        ];

        if ($httpCode === ResponseAlias::HTTP_INTERNAL_SERVER_ERROR && !config('app.debug')) {
            $data['errors'] = [
                'message' => 'Server error',
            ];
        }

        return response()->json($data, $httpCode);
    }
}
