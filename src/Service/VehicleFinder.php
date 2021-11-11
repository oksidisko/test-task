<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\FilterDto;
use App\Entity\Vehicle;
use App\Exception\ApiException;
use App\Repository\VehicleRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;

class VehicleFinder
{
    private VehicleRepository $repository;

    public function __construct(VehicleRepository $repository)
    {
        $this->repository = $repository;
    }

    public function findByFilterdto(FilterDto $filter): array
    {
        return $this->repository->filterByDto($filter);
    }

    public function findById(string $id): Vehicle
    {
        try {
            $uuid = Uuid::fromString($id);
        } catch (\Throwable $e) {
            throw new ApiException('Некорректный ИД!', 0, Response::HTTP_NOT_FOUND);
        }

        $vehicle = $this->repository->findOneById($uuid);

        if ($vehicle) {
            return $vehicle;
        }

        throw new ApiException('Машина с таким ИД не найдена!', 0, Response::HTTP_NOT_FOUND);
    }
}