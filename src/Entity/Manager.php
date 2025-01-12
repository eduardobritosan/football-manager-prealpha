<?php

namespace App\Entity;

use App\Repository\ManagerRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Ignore;

#[ORM\Entity(repositoryClass: ManagerRepository::class)]
class Manager extends Employee
{

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $highestLicense = null;

    #[ORM\OneToOne(inversedBy: 'manager', cascade: ['persist', 'remove'])]
    #[Ignore]
    private ?Club $club = null;

    public function getHighestLicense(): ?string
    {
        return $this->highestLicense;
    }

    public function setHighestLicense(?string $highestLicense): static
    {
        $this->highestLicense = $highestLicense;

        return $this;
    }

    public function getClub(): ?Club
    {
        return $this->club;
    }

    public function setClub(?Club $club): static
    {
        $this->club = $club;

        return $this;
    }
}
