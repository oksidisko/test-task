<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\HistoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

/**
 * @ORM\Entity(repositoryClass=HistoryRepository::class)
 */
class History
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     */
    private Uuid $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $entityClass;

    /**
     * @ORM\Column(type="uuid")
     */
    private Uuid $entityId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $field;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $old;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $new;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private \DateTimeImmutable $changedAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $changedBy;

    public function __construct()
    {
        $this->id = Uuid::v4();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getEntityClass(): ?string
    {
        return $this->entityClass;
    }

    public function setEntityClass(string $entityClass): self
    {
        $this->entityClass = $entityClass;

        return $this;
    }

    public function getEntityId(): ?Uuid
    {
        return $this->entityId;
    }

    public function setEntityId(Uuid $entityId): self
    {
        $this->entityId = $entityId;

        return $this;
    }

    public function getField(): ?string
    {
        return $this->field;
    }

    public function setField(string $field): self
    {
        $this->field = $field;

        return $this;
    }

    public function getOld(): ?string
    {
        return $this->old;
    }

    public function setOld(string $old): self
    {
        $this->old = $old;

        return $this;
    }

    public function getNew(): ?string
    {
        return $this->new;
    }

    public function setNew(string $new): self
    {
        $this->new = $new;

        return $this;
    }

    public function getChangedAt(): ?\DateTimeImmutable
    {
        return $this->changedAt;
    }

    public function setChangedAt(\DateTimeImmutable $changedAt): self
    {
        $this->changedAt = $changedAt;

        return $this;
    }

    public function getChangedBy(): ?string
    {
        return $this->changedBy;
    }

    public function setChangedBy(?string $changedBy): self
    {
        $this->changedBy = $changedBy;

        return $this;
    }
}
