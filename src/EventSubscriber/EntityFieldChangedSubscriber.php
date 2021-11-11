<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Entity\History;
use App\Event\VehicleChangedEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class EntityFieldChangedSubscriber implements EventSubscriberInterface
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            VehicleChangedEvent::NAME => 'onVehicleChanged',
        ];
    }

    public function onVehicleChanged(VehicleChangedEvent $event)
    {
        $vehicle = $event->getVehicle();
        $change = $event->getChange();
        $history = (new History())
            ->setEntityClass(get_class($vehicle))
            ->setEntityId($vehicle->getId())
            ->setField($change->field)
            ->setOld($change->old)
            ->setNew($change->new)
            ->setChangedAt($change->date)
            ->setChangedBy($change->user);

        $this->em->persist($history);
    }
}
