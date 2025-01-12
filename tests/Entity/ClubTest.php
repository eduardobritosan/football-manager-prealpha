<?php


namespace App\Tests\Entity;

use App\Entity\Club;
use App\Entity\Employee;
use App\Entity\Manager;
use PHPUnit\Framework\TestCase;

class ClubTest extends TestCase
{
    public function testGetId()
    {
        $club = new Club();
        $this->assertNull($club->getId());
    }

    public function testGetName()
    {
        $club = new Club();
        $club->setName('Test Club');
        $this->assertEquals('Test Club', $club->getName());
    }

    public function testSetName()
    {
        $club = new Club();
        $club->setName('Test Club');
        $this->assertEquals('Test Club', $club->getName());
    }

    public function testGetBudget()
    {
        $club = new Club();
        $club->setBudget('1000000.00');
        $this->assertEquals('1000000.00', $club->getBudget());
    }

    public function testSetBudget()
    {
        $club = new Club();
        $club->setBudget('1000000.00');
        $this->assertEquals('1000000.00', $club->getBudget());
    }

    public function testGetWorkforce()
    {
        $club = new Club();
        $this->assertInstanceOf(\Doctrine\Common\Collections\Collection::class, $club->getWorkforce());
    }

    public function testGetManager()
    {
        $club = new Club();
        $this->assertNull($club->getManager());
    }
}
