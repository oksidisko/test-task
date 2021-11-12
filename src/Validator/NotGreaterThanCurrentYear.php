<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/** @Annotation */
class NotGreaterThanCurrentYear extends Constraint
{
    public const GREATER_THAN_CURRENT_YEAR = 'greater_than_current_year';

    public string $message = 'Указанный год еще не наступил!';
}