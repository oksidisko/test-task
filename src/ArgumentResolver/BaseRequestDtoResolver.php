<?php

declare(strict_types=1);

namespace App\ArgumentResolver;

use App\Exception\ApiException;
use App\Exception\ApiRequestValidationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class BaseRequestDtoResolver implements ArgumentValueResolverInterface
{
    protected ValidatorInterface $validator;
    protected SerializerInterface $serializer;
    protected string $supportDtoInterface = '';

    public function __construct(ValidatorInterface $validator, SerializerInterface $serializer)
    {
        $this->validator = $validator;
        $this->serializer = $serializer;
    }

    /**
     * @throws \ReflectionException
     */
    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        $reflection = new \ReflectionClass($argument->getType());
        if ($reflection->implementsInterface($this->supportDtoInterface)) {
            return true;
        }

        return false;
    }

    /**
     * @throws ApiException
     */
    public function resolve(Request $request, ArgumentMetadata $argument): \Generator
    {
        $class = $argument->getType();
        $dto = $this->resolveRequest($request, $class);

        $errors = $this->validator->validate($dto);
        if (count($errors) > 0) {
            // Ошибка валидации
            $firstError = $errors->get(0);
            throw new ApiRequestValidationException($firstError->getPropertyPath().': '.$firstError->getMessage());
        }

        yield $dto;
    }

    /** @return mixed */
    abstract protected function resolveRequest(Request $request, string $class);
}
