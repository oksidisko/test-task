<?php

declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;

class ApiRequestValidationException extends ApiException
{
    protected int $httpCode = Response::HTTP_BAD_REQUEST;
    protected $message = 'Ошибка валидации переданных данных';

    public function __construct(string $message = '', int $code = 0)
    {
        parent::__construct($message, $code);
    }
}
