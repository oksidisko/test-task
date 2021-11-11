<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Option;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class OptionFixtures extends Fixture
{
    public const OPTION_RAIN_SENSOR_REFERENCE = 'option-rain_sensor';
    public const OPTION_ABS_REFERENCE = 'option-abs';
    public const OPTION_CONDITIONER_REFERENCE = 'option-conditioner';

    public function load(ObjectManager $manager): void
    {
        $option = (new Option())
            ->setTitle('Датчик дождя')
            ->setCode('rain_sensor');
        $manager->persist($option);
        $this->addReference(self::OPTION_RAIN_SENSOR_REFERENCE, $option);

        $option = (new Option())
            ->setTitle('АБС')
            ->setCode('abs');
        $manager->persist($option);
        $this->addReference(self::OPTION_ABS_REFERENCE, $option);

        $option = (new Option())
            ->setTitle('Кондиционер')
            ->setCode('conditioner');
        $manager->persist($option);
        $this->addReference(self::OPTION_CONDITIONER_REFERENCE, $option);

        $manager->flush();
    }
}
