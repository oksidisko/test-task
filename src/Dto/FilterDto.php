<?php

declare(strict_types=1);

namespace App\Dto;

use App\ArgumentResolver\QueryRequestDtoInterface;
use App\Validator as CustomAssert;
use Symfony\Component\Validator\Constraints as Assert;

class FilterDto implements QueryRequestDtoInterface
{
    /**
     * @Assert\Type("string")
     * @Assert\Length(min=1, max=255)
     */
    public ?string $brand = null;
    /**
     * @Assert\Type("bool")
     */
    public ?bool $isNew = null;
    /**
     * @Assert\Type("integer")
     * @Assert\Positive
     * @Assert\GreaterThan(1900)
     * @CustomAssert\NotGreaterThanCurrentYear()
     */
    public ?int $yearFrom = null;
    /**
     * @Assert\Type("integer")
     * @Assert\Positive
     * @Assert\GreaterThan(1900)
     * @CustomAssert\NotGreaterThanCurrentYear()
     */
    public ?int $yearTo = null;
    /**
     * @Assert\Type("string")
     * @Assert\Length(min=1, max=13)
     * @Assert\Regex(pattern="/^([0-9]*|\d*\.\d{1}?\d*)/", message="Некорректно указана цена")
     */
    public ?string $priceFrom = null;
    /**
     * @Assert\Type("string")
     * @Assert\Length(min=1, max=13)
     * @Assert\Regex(pattern="/^([0-9]*|\d*\.\d{1}?\d*)$/", message="Некорректно указана цена")
     */
    public ?string $priceTo = null;
    public array $option = [];

    public static function fromParam(): ?string
    {
        return 'filter';
    }
}
