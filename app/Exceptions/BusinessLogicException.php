<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

abstract class BusinessLogicException extends Exception
{
    public const VALIDATION_FAILED = 422;
    public const NOT_AUTHENTICATED = 403;
    public const SERVER_ERROR = 500;

    private int $httpStatusCode = ResponseAlias::HTTP_BAD_REQUEST;

    public function getHttpStatusCode(): int
    {
        return $this->httpStatusCode;
    }

    abstract public function getStatus(): int;

    abstract public function getStatusMessage(): string;
}
