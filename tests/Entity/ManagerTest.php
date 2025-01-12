<?php

namespace App\Tests\Entity;

use App\Entity\Manager;
use App\Entity\Club;
use PHPUnit\Framework\TestCase;

class ManagerTest extends TestCase
{
    public function testGetAndSetHighestLicense()
    {
        $manager = new Manager();
        $this->assertNull($manager->getHighestLicense());

        $highestLicense = 'UEFA Pro';
        $manager->setHighestLicense($highestLicense);
        $this->assertEquals($highestLicense, $manager->getHighestLicense());
    }

    public function testGetAndSetClub()
    {
        $manager = new Manager();
        $this->assertNull($manager->getClub());

        $club = new Club();
        $manager->setClub($club);
        $this->assertSame($club, $manager->getClub());
    }
}


