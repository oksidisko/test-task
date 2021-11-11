<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\FieldChangeDto;
use App\Entity\Vehicle;
use App\Event\VehicleChangedEvent;
use App\Exception\ApiRequestValidationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class VehicleFixer
{
    const MILEAGE_MILE = 'miles';
    const MILEAGE_PERCENT = 'percent';
    const MILEAGE_MAX_DROP_PERCENT = 95;
    const MILEAGE_CONVERT_TO_NEW = 5000;

    private EntityManagerInterface $em;
    private EventDispatcherInterface $dispatcher;
    private bool $saveAfterFix = false;

    public function __construct(EntityManagerInterface $em, EventDispatcherInterface $dispatcher)
    {
        $this->em = $em;
        $this->dispatcher = $dispatcher;
    }

    public static function getTypes(): array
    {
        return [
            self::MILEAGE_MILE,
            self::MILEAGE_PERCENT,
        ];
    }

    public function saveAfterFix(bool $saveAfterFix): self
    {
        $this->saveAfterFix = $saveAfterFix;

        return $this;
    }

    public function dropMileage(Vehicle $vehicle, int $dropValue, string $dropType): Vehicle
    {
        $oldMileage = $vehicle->getMileage();
        switch ($dropType) {
            case self::MILEAGE_MILE:
                $newMileage = $this->dropValue($vehicle->getMileage(), $dropValue);
                break;
            case self::MILEAGE_PERCENT:
                $newMileage = $this->dropPercent($vehicle->getMileage(), $dropValue);
                break;
            default:
                $newMileage = $vehicle->getMileage();
        }

        if ($newMileage < $oldMileage * (100 - self::MILEAGE_MAX_DROP_PERCENT) / 100) {
            throw new ApiRequestValidationException('Нельзя скрутить больше 95% текущего пробега авто ни процентами ни милями!');
        }

        if ($oldMileage !== $newMileage) {
            $vehicle->setMileage($newMileage);
            // Если пробег машины стал меньше 5000, ставим флаг, что она новая
            if ($newMileage < self::MILEAGE_CONVERT_TO_NEW) {
                $vehicle->setIsNew(true);
            }

            $change = new FieldChangeDto();
            $change->new = (string)$newMileage;
            $change->old = (string)$oldMileage;
            $change->field = 'mileage';
            $change->date = new \DateTimeImmutable();
            // сохраняем пользователя, если он есть
            //$change->user = $currentUser ?? null;

            $event = new VehicleChangedEvent($vehicle, $change);
            $this->dispatcher->dispatch($event, VehicleChangedEvent::NAME);

            if ($this->saveAfterFix) {
                $this->em->flush();
            }
        }

        return $vehicle;
    }

    private function dropValue(int $initValue, int $dropValue): int
    {
        return $initValue - $dropValue;
    }

    private function dropPercent(int $initValue, int $dropPercent): int
    {
        return (int) round($initValue - $initValue * $dropPercent / 100);
    }
}