<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class NotGreaterThanCurrentYearValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof NotGreaterThanCurrentYear) {
            throw new UnexpectedTypeException($constraint, NotGreaterThanCurrentYear::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_int($value)) {
            throw new UnexpectedValueException($value, 'integer');
        }

        if ($value > (int)(new \DateTimeImmutable())->format('Y')) {
            $this->context->buildViolation($constraint->message)
                ->setCode(NotGreaterThanCurrentYear::GREATER_THAN_CURRENT_YEAR)
                ->addViolation();
        }
    }
}