<?php

declare(strict_types=1);

namespace App\Controller\Api\V1;

use App\Dto\DropMileageDto;
use App\Dto\FilterDto;
use App\Exception\ApiException;
use App\Service\VehicleFinder;
use App\Service\VehicleFixer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

/** @Route("/api/v1", name="api_v1_") */
class ApiController extends AbstractController
{
    private VehicleFinder $finder;

    public function __construct(VehicleFinder $finder)
    {
        $this->finder = $finder;
    }

    /** @Route("/", name="index", methods={"GET"}) */
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new api!',
        ]);
    }

    /** @Route("/vehicles", name="vehicles", methods={"GET"}) */
    public function vehicles(FilterDto $filter): JsonResponse
    {
        $vehicles = $this->finder->findByFilterdto($filter);

        return $this->json($vehicles);
    }

    /** @Route("/vehicles/{id}/drop-mileage", name="vehicles_drop-mileage", methods={"POST"}) */
    public function dropMileage(DropMileageDto $dto, string $id, VehicleFixer $fixer): JsonResponse
    {
        $vehicle = $this->finder->findById($id);
        $fixer->saveAfterFix(true)
            ->dropMileage($vehicle, $dto->value, $dto->type);

        return $this->json('ok');
    }
}
