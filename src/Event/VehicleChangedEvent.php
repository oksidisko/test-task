<?php

declare(strict_types=1);

namespace App\Event;

use App\Dto\FieldChangeDto;
use App\Entity\Vehicle;
use Symfony\Contracts\EventDispatcher\Event;

class VehicleChangedEvent extends Event
{
    public const NAME = 'vehicle.changed';

    protected Vehicle $vehicle;
    protected FieldChangeDto $change;

    public function __construct(Vehicle $vehicle, FieldChangeDto $change)
    {
        $this->vehicle = $vehicle;
        $this->change = $change;
    }

    public function getVehicle(): Vehicle
    {
        return $this->vehicle;
    }

    public function getChange(): FieldChangeDto
    {
        return $this->change;
    }
}
