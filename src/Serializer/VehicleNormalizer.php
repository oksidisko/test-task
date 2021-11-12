<?php

declare(strict_types=1);

namespace App\Serializer;

use App\Entity\Vehicle;
use NumberFormatter;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;

class VehicleNormalizer implements ContextAwareNormalizerInterface
{
    public function normalize($object, string $format = null, array $context = []): array
    {
        $formatter = new NumberFormatter('ru', NumberFormatter::CURRENCY);
        /** @var Vehicle $object */
        $data = [
            'id' => $object->getId()->toRfc4122(),
            'brand' => $object->getBrand()->getTitle(),
            'isNew' => $object->getIsNew() ? 'Новый' : 'Подержаный',
            'year' => $object->getYear(),
            'mileage' => $object->getMileage(),
            'price' => $formatter->formatCurrency((float)$object->getPrice(), 'RUR'),
            'option' => [],
        ];

        foreach ($object->getOptions() as $option) {
            $data['option'][] = $option->getTitle();
        }

        return $data;
    }

    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $data instanceof Vehicle;
    }
}
