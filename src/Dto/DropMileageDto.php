<?php

declare(strict_types=1);

namespace App\Dto;

use App\ArgumentResolver\JsonRequestDtoInterface;
use Symfony\Component\Validator\Constraints as Assert;

class DropMileageDto implements JsonRequestDtoInterface
{
    /**
     * @Assert\NotBlank
     * @Assert\Type("string")
     * @Assert\Choice(callback={"App\Service\VehicleFixer", "getTypes"})
     */
    public string $type;
    /**
     * @Assert\Type("integer")
     * @Assert\Positive
     * @Assert\GreaterThan(0)
     */
    public int $value;
}
