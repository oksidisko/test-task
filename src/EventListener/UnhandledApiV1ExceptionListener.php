<?php

declare(strict_types=1);

namespace App\EventListener;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class UnhandledApiV1ExceptionListener
{
    private ?Request $request;
    private LoggerInterface $logger;

    public function __construct(RequestStack $request, LoggerInterface $logger)
    {
        $this->request = $request->getCurrentRequest();
        $this->logger = $logger;
    }

    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        if ($this->request && !str_contains($this->request->getPathInfo(), 'api/v1')) {
            return;
        }

        $this->logger->critical('Unhandled exception', ['message' => $exception->getMessage()]);

        $responseData = [
            'error' => [
                'code' => 0,
                'message' => 'Внутренняя ошибка сервера',
            ],
        ];

        $event->setResponse(new JsonResponse($responseData, Response::HTTP_INTERNAL_SERVER_ERROR));
    }
}
