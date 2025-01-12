<?php

namespace App\Tests\Entity;

use App\Entity\Employee;
use App\Entity\Club;
use PHPUnit\Framework\TestCase;

class EmployeeTest extends TestCase
{
    public function testGetAndSetNif()
    {
        $employee = new Employee();
        $nif = '123456789';
        $employee->setNif($nif);
        $this->assertEquals($nif, $employee->getNif());
    }

    public function testGetAndSetName()
    {
        $employee = new Employee();
        $name = 'John Doe';
        $employee->setName($name);
        $this->assertEquals($name, $employee->getName());
    }

    public function testGetAndSetSalary()
    {
        $employee = new Employee();
        $salary = '50000.00';
        $employee->setSalary($salary);
        $this->assertEquals($salary, $employee->getSalary());
    }
}
