<?php

declare(strict_types=1);

namespace App\ArgumentResolver;

interface QueryRequestDtoInterface
{
    public static function fromParam(): ?string;
}
