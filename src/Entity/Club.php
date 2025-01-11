<?php

namespace App\Entity;

use App\Repository\ClubRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClubRepository::class)]
class Club
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 11, scale: 2)]
    private ?string $budget = null;

    /**
     * @var Collection<int, Employee>
     */
    #[ORM\OneToMany(targetEntity: Employee::class, mappedBy: 'currentClub')]
    private Collection $workforce;

    #[ORM\OneToOne(mappedBy: 'club', cascade: ['persist', 'remove'])]
    private ?Manager $manager = null;

    public function __construct()
    {
        $this->workforce = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getBudget(): ?string
    {
        return $this->budget;
    }

    public function setBudget(string $budget): static
    {
        $this->budget = $budget;

        return $this;
    }

    /**
     * @return Collection<int, Employee>
     */
    public function getWorkforce(): Collection
    {
        return $this->workforce;
    }

    public function addWorkforce(Employee $workforce): static
    {
        if (!$this->workforce->contains($workforce)) {
            $this->workforce->add($workforce);
            $workforce->setCurrentClub($this);
        }

        return $this;
    }

    public function removeWorkforce(Employee $workforce): static
    {
        if ($this->workforce->removeElement($workforce)) {
            // set the owning side to null (unless already changed)
            if ($workforce->getCurrentClub() === $this) {
                $workforce->setCurrentClub(null);
            }
        }

        return $this;
    }

    public function getManager(): ?Manager
    {
        return $this->manager;
    }

    public function setManager(?Manager $manager): static
    {
        // unset the owning side of the relation if necessary
        if ($manager === null && $this->manager !== null) {
            $this->manager->setClub(null);
        }

        // set the owning side of the relation if necessary
        if ($manager !== null && $manager->getClub() !== $this) {
            $manager->setClub($this);
        }

        $this->manager = $manager;

        return $this;
    }
}
