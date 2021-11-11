<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Vehicle;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class VehicleFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $vehicle = new Vehicle();
        $vehicle->setBrand($this->getReference(BrandFixtures::BRAND_BMV_REFERENCE))
            ->setIsNew(true)
            ->setYear(2020)
            ->setPrice('1000000.00')
            ->addOption($this->getReference(OptionFixtures::OPTION_RAIN_SENSOR_REFERENCE))
            ->addOption($this->getReference(OptionFixtures::OPTION_ABS_REFERENCE))
            ->addOption($this->getReference(OptionFixtures::OPTION_CONDITIONER_REFERENCE))
            ->setMileage(100);
        $manager->persist($vehicle);

        $vehicle = new Vehicle();
        $vehicle->setBrand($this->getReference(BrandFixtures::BRAND_VOLVO_REFERENCE))
            ->setIsNew(false)
            ->setYear(2010)
            ->setPrice('500000.00')
            ->addOption($this->getReference(OptionFixtures::OPTION_CONDITIONER_REFERENCE))
            ->setMileage(300000);
        $manager->persist($vehicle);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            BrandFixtures::class,
            OptionFixtures::class,
        ];
    }
}
