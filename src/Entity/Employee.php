<?php

namespace App\Entity;

use App\Repository\EmployeeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Serializer\Attribute\MaxDepth;

#[ORM\Entity(repositoryClass: EmployeeRepository::class)]
#[ORM\InheritanceType("JOINED")]
#[ORM\DiscriminatorColumn(name: "type", type: "string")]
#[ORM\DiscriminatorMap(["player" => Player::class, "manager" => Manager::class])]
class Employee
{

    #[ORM\Column(length: 9)]
    #[ORM\Id]
    #[ORM\GeneratedValue("NONE")]
    private ?string $nif = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 11, scale: 2, nullable: true)]
    private ?string $salary = null;

    #[ORM\ManyToOne(inversedBy: 'workforce')]
    #[Ignore]
    private ?Club $currentClub = null;

    public function getNif(): ?string
    {
        return $this->nif;
    }

    public function setNif(string $nif): static
    {
        $this->nif = $nif;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSalary(): ?string
    {
        return $this->salary;
    }

    public function setSalary(string $salary): static
    {
        $this->salary = $salary;

        return $this;
    }

    public function getCurrentClub(): ?Club
    {
        return $this->currentClub;
    }

    public function setCurrentClub(?Club $currentClub): static
    {
        $this->currentClub = $currentClub;

        return $this;
    }
}
