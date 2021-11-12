<?php

declare(strict_types=1);

namespace App\Repository;

use App\Dto\FilterDto;
use App\Entity\Vehicle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;

/**
 * @method Vehicle|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vehicle|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vehicle[]    findAll()
 * @method Vehicle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VehicleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vehicle::class);
    }

    public function filterByDto(FilterDto $dto): array
    {
        $qb = $this->createQueryBuilder('v');

        if ($dto->brand) {
            $qb->join('v.brand', 'b')
                ->andWhere('LOWER(b.code) = :brand')
                ->setParameter('brand', strtolower($dto->brand));
        }

        if (!is_null($dto->isNew)) {
            $qb->andWhere('v.isNew = :isNew')
                ->setParameter('isNew', $dto->isNew);
        }

        if ($dto->yearFrom) {
            $qb->andWhere('v.year >= :yearFrom')
                ->setParameter('yearFrom', $dto->yearFrom);
        }

        if ($dto->yearTo) {
            $qb->andWhere('v.year <= :yearTo')
                ->setParameter('yearTo', $dto->yearTo);
        }

        if ($dto->priceFrom) {
            $qb->andWhere('v.price >= :priceFrom')
                ->setParameter('priceFrom', $dto->priceFrom);
        }

        if ($dto->priceTo) {
            $qb->andWhere('v.price <= :priceTo')
                ->setParameter('priceTo', $dto->priceTo);
        }

        if ($dto->option) {
            $qb->join('v.options', 'o')
                ->andWhere('o.code IN (:options)')
                ->setParameter('options', implode(',', $dto->option));
        }

        return $qb->getQuery()->getResult();
    }

    public function getVehicleWithMileageCount(int $mileage): int
    {
        $qb = $this->createQueryBuilder('v')
            ->select('COUNT(v.id) AS cnt')
            ->andWhere('v.mileage >= :mileage')
            ->setParameter('mileage', $mileage);

        try {
            return (int)$qb->getQuery()->getSingleScalarResult();
        } catch (NoResultException|NonUniqueResultException $e) {
            return 0;
        }
    }

    public function getVehicleWithMileagePaginated(int $mileage, int $limit = 0, int $offset = 0): array
    {
        $qb = $this->createQueryBuilder('v')
            ->where('v.mileage >= :mileage')
            ->setParameter('mileage', $mileage);

        if ($limit) {
            $qb->setMaxResults($limit);
        }

        if ($offset) {
            $qb->setFirstResult($offset);
        }

        return $qb->getQuery()->getResult();
    }

    public function findOneById(Uuid $id): ?Vehicle
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.id = :val')
            ->setParameter('val', $id, 'uuid')
            ->getQuery()
            ->getOneOrNullResult();
    }
}
