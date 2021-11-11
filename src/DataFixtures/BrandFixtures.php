<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Brand;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BrandFixtures extends Fixture
{
    public const BRAND_BMV_REFERENCE = 'brand-bmv';
    public const BRAND_VOLVO_REFERENCE = 'brand-volvo';

    public function load(ObjectManager $manager): void
    {
        $brand = (new Brand())
            ->setTitle('BMV')
            ->setCode('bmv');
        $manager->persist($brand);
        $this->addReference(self::BRAND_BMV_REFERENCE, $brand);

        $brand = (new Brand())
            ->setTitle('Volvo')
            ->setCode('volvo');
        $manager->persist($brand);
        $this->addReference(self::BRAND_VOLVO_REFERENCE, $brand);

        $manager->flush();
    }
}
