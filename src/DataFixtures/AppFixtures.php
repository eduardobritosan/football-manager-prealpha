<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Club;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $club = new Club;
        $club->setName("Club Deportivo Tenerife");
        $club->setBudget("2000000.00");

        $manager->persist($club);

        $club = new Club;
        $club->setName("Union Deportiva Las Palmas");
        $club->setBudget("4000000.00");

        $manager->persist($club);

        $manager->flush();
    }
}
