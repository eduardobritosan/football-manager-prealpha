<?php

namespace App\Entity;

use App\Repository\ManagerRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ManagerRepository::class)]
class Manager extends Employee
{

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $highestLicense = null;

    public function getHighestLicense(): ?string
    {
        return $this->highestLicense;
    }

    public function setHighestLicense(?string $highestLicense): static
    {
        $this->highestLicense = $highestLicense;

        return $this;
    }
}
