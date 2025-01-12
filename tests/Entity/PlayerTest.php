<?php

namespace App\Tests\Entity;

use App\Entity\Player;
use PHPUnit\Framework\TestCase;

class PlayerTest extends TestCase
{
    public function testGetReleaseClause()
    {
        $player = new Player();
        $this->assertNull($player->getReleaseClause());

        $releaseClause = '1000000.00';
        $player->setReleaseClause($releaseClause);
        $this->assertEquals($releaseClause, $player->getReleaseClause());
    }

    public function testSetReleaseClause()
    {
        $player = new Player();
        $releaseClause = '500000.00';
        $player->setReleaseClause($releaseClause);
        $this->assertEquals($releaseClause, $player->getReleaseClause());

        $player->setReleaseClause(null);
        $this->assertNull($player->getReleaseClause());
    }
}
