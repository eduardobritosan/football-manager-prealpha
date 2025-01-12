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
use App\Service\NotificationService;

/**
 * @extends ServiceEntityRepository<Club>
 */
class ClubRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private readonly EmployeeRepository $employees,
        private readonly EntityManagerInterface $objectManager,
        private readonly NotificationService $notifier
    ) {
        parent::__construct($registry, Club::class);
    }

    /**
     * Calculates and returns the available budget for a club.
     *
     * This method retrieves a club by its ID, calculates the total salary of all employees
     * in the club's workforce, and then subtracts this total from the club's budget to
     * determine the available budget.
     *
     * @param int $id The ID of the club.
     * @return float The available budget for the club.
     */
    public function getAvailableBudget(int $id)
    {
        $club = $this->findOneBy(['id' => $id]);
        $totalEmployeeSalary = 0;
        foreach ($club->getWorkforce() as $employee) {
            $totalEmployeeSalary += $employee->getSalary();
        }
        return (float)$club->getBudget() - (float)$totalEmployeeSalary;
    }

    /**
     * Signs an employee to a club.
     *
     * @param SignEmployeeDto $dto Data transfer object containing employee sign-up details.
     * @param Employee $employee The employee to be signed.
     * @param Club $club The club to which the employee will be signed.
     * @return SignEmployeeResponseDto Response DTO indicating the result of the sign-up process.
     */
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
            // Disabled as issues arose with SSL certificate of application. Saved as a TODO.
            //$this->notifier->notify($employee->getName(), $club->getId(), 'sign', 'email');
            return SignEmployeeResponseDto::of("Player correctly signed up");
        }
        return SignEmployeeResponseDto::of("Issues with sign up");
    }

    /**
     * Releases an employee from a club.
     *
     * This method removes the specified employee from the club's workforce, sets the employee's current club to null,
     * and sets the employee's salary to "0". It then saves the changes to the club and employee.
     * 
     * @param SignEmployeeDto $dto Data transfer object containing information about the employee to be released.
     * @param Employee $employee The employee to be released.
     * @param Club $club The club from which the employee is to be released.
     * 
     * @return SignEmployeeResponseDto A response DTO indicating the result of the release operation.
     */
    public function releaseEmployee(SignEmployeeDto $dto, Employee $employee, Club $club)
    {
        if ($club->getWorkforce()->contains($employee)) {
            $club->getWorkforce()->removeElement($employee);
            $employee->setCurrentClub(null);
            $employee->setSalary("0");
            $this->saveChangesClubEmployee($club, $employee);
            // Disabled as issues arose with SSL certificate of application. Saved as a TODO.
            //$this->notifier->notify($employee->getName(), $club->getId(), 'release', 'email');
            return SignEmployeeResponseDto::of("Player correctly released");
        }
        return SignEmployeeResponseDto::of("Issues with release");
    }

    /**
     * Calculates the total salary of all employees in the workforce of a club.
     *
     * @param int $id The ID of the club.
     * @return float The total salary of all employees in the club's workforce.
     */
    public function getTotalWorkforceSalary(int $id)
    {
        $club = $this->findOneBy(['id' => $id]);
        $totalEmployeeSalary = 0;
        foreach ($club->getWorkforce() as $employee) {
            $totalEmployeeSalary += $employee->getSalary();
        }
        return (float)$totalEmployeeSalary;
    }

    /**
     * Saves changes to both the Club and Employee entities.
     *
     * This method persists the given Club and Employee entities using the object manager
     * and then flushes the changes to the database.
     *
     * @param Club $club The club entity to be persisted.
     * @param Employee $employee The employee entity to be persisted.
     *
     * @return void
     */
    private function saveChangesClubEmployee(Club $club, Employee $employee)
    {
        $this->objectManager->persist($club);
        $this->objectManager->persist($employee);
        $this->objectManager->flush();
    }
}
