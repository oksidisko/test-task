<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class ApiException extends Exception
{
    protected int $httpCode = Response::HTTP_BAD_REQUEST;

    public function __construct(string $message = '', int $code = 0, int $httpCode = null)
    {
        parent::__construct($message, $code);

        $this->httpCode = $httpCode ?: $this->httpCode;
    }

    public function getHttpCode(): int
    {
        return $this->httpCode;
    }

    public function setHttpCode(int $httpCode): void
    {
        $this->httpCode = $httpCode;
    }

    protected $message = 'Внутренняя ошибка АПИ';
}
