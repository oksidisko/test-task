<?php

declare(strict_types=1);

namespace App\ArgumentResolver;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;

class JsonRequestDtoResolver extends BaseRequestDtoResolver
{
    protected string $supportDtoInterface = JsonRequestDtoInterface::class;

    protected function resolveRequest(Request $request, string $class)
    {
        return $this->serializer->deserialize($request->getContent(), $class, 'json', [
            AbstractObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => true,
        ]);
    }
}
