<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Club;
use App\Entity\Player;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $club = new Club;
        $club->setName("Club Deportivo Tenerife");
        $club->setBudget("2000000.00");

        $manager->persist($club);

        $club = new Club;
        $club->setName("Lanzarote");
        $club->setBudget("4000000.00");

        $manager->persist($club);

        $club = new Club;
        $club->setName("Fuerteventura");
        $club->setBudget("4000000.00");

        $manager->persist($club);

        $club = new Club;
        $club->setName("La Graciosa");
        $club->setBudget("4000000.00");

        $manager->persist($club);

        $player = new Player;
        $player->setName("Juan 1");
        $player->setNif("Y1234567R");

        $manager->persist($player);

        $player = new Player;
        $player->setName("Juan 2");
        $player->setNif("Y1234567F");

        $manager->persist($player);

        $player = new Player;
        $player->setName("Juan 3");
        $player->setNif("Y1234567Q");

        $manager->persist($player);

        $player = new Player;
        $player->setName("Juan 4");
        $player->setNif("Y1234567O");

        $manager->persist($player);

        $player = new Player;
        $player->setName("Juan 5");
        $player->setNif("Y1234567T");

        $manager->persist($player);

        $player = new Player;
        $player->setName("Juan 1");
        $player->setNif("Y1234567H");

        $manager->persist($player);

        $manager->flush();
    }
}
