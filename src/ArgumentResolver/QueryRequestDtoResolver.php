<?php

declare(strict_types=1);

namespace App\ArgumentResolver;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;

class QueryRequestDtoResolver extends BaseRequestDtoResolver
{
    protected string $supportDtoInterface = QueryRequestDtoInterface::class;

    /**
     * @throws ExceptionInterface
     */
    protected function resolveRequest(Request $request, string $class)
    {
        return $this->serializer->denormalize($request->query->all($class::fromParam()), $class, null, [
            AbstractObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => true,
        ]);
    }
}
