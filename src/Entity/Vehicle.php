<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\VehicleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

/**
 * @ORM\Entity(repositoryClass=VehicleRepository::class)
 */
class Vehicle
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     */
    private Uuid $id;

    /**
     * @ORM\ManyToOne(targetEntity=Brand::class, inversedBy="vehicles")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private Brand $brand;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isNew;

    /**
     * @ORM\Column(type="integer")
     */
    private int $year;

    /**
     * @ORM\Column(type="decimal", precision=12, scale=2)
     */
    private string $price;

    /**
     * @ORM\ManyToMany(targetEntity=Option::class, inversedBy="vehicles")
     */
    private Collection $options;

    /**
     * @ORM\Column(type="integer")
     */
    private int $mileage;

    public function __construct()
    {
        $this->id = Uuid::v4();
        $this->options = new ArrayCollection();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getBrand(): ?Brand
    {
        return $this->brand;
    }

    public function setBrand(?Brand $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    public function getIsNew(): ?bool
    {
        return $this->isNew;
    }

    public function setIsNew(bool $isNew): self
    {
        $this->isNew = $isNew;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getOptions(): Collection
    {
        return $this->options;
    }

    public function addOption(Option $option): self
    {
        if (!$this->options->contains($option)) {
            $this->options[] = $option;
        }

        return $this;
    }

    public function removeOption(Option $option): self
    {
        $this->options->removeElement($option);

        return $this;
    }

    public function getMileage(): ?int
    {
        return $this->mileage;
    }

    public function setMileage(int $mileage): self
    {
        $this->mileage = $mileage;

        return $this;
    }
}
