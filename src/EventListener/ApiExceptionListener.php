<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Exception\ApiException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ApiExceptionListener
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        if (!$exception instanceof ApiException) {
            return;
        }

        $this->logger->critical('Api exception', ['message' => $exception->getMessage()]);

        $responseData = [
            'error' => [
                'code' => $exception->getCode(),
                'message' => $exception->getMessage(),
            ],
        ];

        $event->setResponse(new JsonResponse($responseData, $exception->getHttpCode()));
    }
}
