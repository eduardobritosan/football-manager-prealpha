<?php

namespace App\Repository;

use App\Dto\SignEmployeeDto;
use App\Dto\SignEmployeeResponseDto;
use App\Entity\Club;
use App\Entity\Employee;
use App\Entity\Manager;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\EmployeeRepository;

/**
 * @extends ServiceEntityRepository<Club>
 */
class ClubRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private readonly EmployeeRepository $employees, private readonly EntityManagerInterface $objectManager)
    {
        parent::__construct($registry, Club::class);
    }

    public function getAvailableBudget(int $id)
    {
        $club = $this->findOneBy(['id' => $id]);
        $totalEmployeeSalary = 0;
        foreach ($club->getWorkforce() as $employee) {
            $totalEmployeeSalary += $employee->getSalary();
        }
        return (float)$club->getBudget() - (float)$totalEmployeeSalary;
    }

    public function signEmployee(SignEmployeeDto $dto, Employee $employee, Club $club)
    {
        $isManager = $employee instanceof Manager;
        if ($isManager and $club->getManager()) {
            return SignEmployeeResponseDto::of("This club already has a manager.");
        }
        $totalEmployeeSalary = $this->getAvailableBudget($club->getId());
        if (
            ($totalEmployeeSalary - $dto->getSalary()) > 0 and
            !$club->getWorkforce()->contains($employee)
        ) {
            $club->getWorkforce()->add($employee);
            if ($isManager) $club->setManager($employee);
            $employee->setCurrentClub($club);
            $employee->setSalary(($dto->getSalary()));
            $this->saveChangesClubEmployee($club, $employee);
            return SignEmployeeResponseDto::of("Player correctly signed up");
        }
        return SignEmployeeResponseDto::of("Issues with sign up");
    }

    public function releaseEmployee(SignEmployeeDto $dto, Employee $employee, Club $club)
    {
        if ($club->getWorkforce()->contains($employee)) {
            $club->getWorkforce()->removeElement($employee);
            $employee->setCurrentClub(null);
            $employee->setSalary("0");
            $this->saveChangesClubEmployee($club, $employee);
            return SignEmployeeResponseDto::of("Player correctly released");
        }
        return SignEmployeeResponseDto::of("Issues with release");
    }

    public function getTotalWorkforceSalary(int $id)
    {
        $club = $this->findOneBy(['id' => $id]);
        $totalEmployeeSalary = 0;
        foreach ($club->getWorkforce() as $employee) {
            $totalEmployeeSalary += $employee->getSalary();
        }
        return (float)$totalEmployeeSalary;
    }

    private function saveChangesClubEmployee(Club $club, Employee $employee)
    {
        $this->objectManager->persist($club);
        $this->objectManager->persist($employee);
        $this->objectManager->flush();
    }
}
