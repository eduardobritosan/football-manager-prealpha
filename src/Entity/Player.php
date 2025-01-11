<?php

namespace App\Entity;

use App\Repository\PlayerRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlayerRepository::class)]
class Player extends Employee
{

    #[ORM\Column(type: Types::DECIMAL, precision: 11, scale: 2, nullable: true)]
    private ?string $releaseClause = null;

    public function getReleaseClause(): ?string
    {
        return $this->releaseClause;
    }

    public function setReleaseClause(?string $releaseClause): static
    {
        $this->releaseClause = $releaseClause;

        return $this;
    }
}
